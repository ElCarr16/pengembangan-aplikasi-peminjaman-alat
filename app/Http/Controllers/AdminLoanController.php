<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Models\Tool;
use App\Models\ActivityLog; // Pastikan model ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoanController extends Controller
{
    // READ: Tampilkan semua data
    public function index()
    {
        $loans = loan::with(['user', 'tool'])->latest()->paginate(10);
        return view('admin.loans.index', compact('loans'));
    }
    // CREATE: Form tambah
    public function create()
    {
        // Ambil user yang rolenya peminjam saja
        $users = User::where('role', 'peminjam')->get();
        // Ambil semua alat
        $tools = tool::all();
        return view('admin.loans.create', compact('users', 'tools'));
    }
    // STORE: Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'tool_id' => 'required',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required'
        ]);

        $tool = Tool::findOrFail($request->tool_id);

        // Validasi stok sebelum disetujui
        if ($request->status == 'disetujui' && $tool->stok < $request->jumlah) {
            return back()->with('error', 'Stok alat tidak cukup untuk jumlah yang diminta.');
        }

        $loan = Loan::create([
            'user_id' => $request->user_id,
            'tool_id' => $request->tool_id,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'status' => $request->status,
            'petugas_id' => Auth::id()
        ]);

        // Kurangi stok sesuai jumlah jika disetujui
        if ($request->status == 'disetujui') {
            $tool->decrement('stok', $request->jumlah);
        }

        ActivityLog::record('Create Loan', 'Admin membuat data pinjaman baru');

        return redirect()->route('admin.loans.index')->with('success', 'Data peminjaman berhasil dibuat.');
    }
    // EDIT: Form edit
    public function edit($id)
    {
        $loan = loan::findOrFail($id);
        $users = User::where('role', 'peminjam')->get();
        $tools = tool::all();
        return view('admin.loans.edit', compact('loan', 'users', 'tools'));
    }
    // UPDATE: Simpan perubahan
    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $tool = Tool::findOrFail($request->tool_id);

        $request->validate([
            'user_id' => 'required',
            'tool_id' => 'required',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required'
        ]);

        // LOGIKA STOK
        if ($loan->status == 'pending' && $request->status == 'disetujui') {
            if ($tool->stok < $request->jumlah) {
                return back()->with('error', 'Stok alat tidak cukup untuk jumlah yang diminta.');
            }
            $tool->decrement('stok', $request->jumlah);
        } elseif ($loan->status == 'disetujui' && $request->status == 'kembali') {
            $tool->increment('stok', $loan->jumlah);
            $request->merge(['tanggal_kembali_aktual' => now()]);
        } elseif ($loan->status == 'disetujui' && $request->status == 'pending') {
            $tool->increment('stok', $loan->jumlah);
        }

        $loan->update([
            'user_id' => $request->user_id,
            'tool_id' => $request->tool_id,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'status' => $request->status,
            'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual ?? $loan->tanggal_kembali_aktual
        ]);

        return redirect()->route('admin.loans.index')->with('success', 'Data berhasil diperbarui.');
    }
    // DELETE: Hapus data
    public function destroy($id)
    {
        $loan = loan::findOrFail($id);
        // Jika menghapus data yang statusnya masih 'disetujui' (sedang dipinjam), kembalikan stok
        if ($loan->status == 'disetujui') {
            $loan->tool->increment('stok');
        }
        $loan->delete();
        return redirect()->route('admin.loans.index')->with('success', 'Data peminjaman dihapus.');
    }
}
