<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\loan;
use App\Models\tool;
use App\Models\ActivityLog; // mencatat log aktivitas


class AdminReturnController extends Controller
{
    //menampilkan riwayat pengembalian
    public function index()
    {
        // ambil data yang hanya statusnya 'kembali'
        $returns = loan::with(['user', 'tool'])
        ->where('status', 'kembali')
        ->latest('tanggal_kembali_aktual')->paginate(10);
        return view('admin.returns.index', compact('returns'));
    }
    // menampilkan daftar alat yang sedang dipinjam untuk proses pengembalian
    public function create()
    {
        // ambil data yang hanya statusnya 'disetujui' (sedang dipinjam)
        $activeloans = loan::with(['user', 'tool'])
        ->where('status', 'disetujui')
        ->latest()
        ->get();
        return view('admin.returns.create', compact('activeloans'));
    }
    // proses pengembalian alat
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'denda' => 'nullable|integer|min:0' // denda opsional, bisa 0
        ]);
        $loan = loan::findOrFail($request->loan_id);
        // pastikan statusnya sedang dipinjam
        if ($loan->status != 'disetujui') {
            return back()->with('error', 'Data peminjaman tidak valid untuk proses pengembalian.');
        }
        // update status & tanggal
        $loan->update([
            'status' => 'kembali',
            'tanggal_kembali_aktual' => now(),
            'denda' => $request->denda ?? 0 //jika tabel loans punya kolom denda
        ]);
        // kembalikan stok alat
        $tool = tool::findOrFail($loan->tool_id);
        $tool->increment('stok');
        // catat log aktivitas
        ActivityLog::record('Pengembalian Alat', "Alat '{$tool->nama_alat}' dikembalikan oleh '{$loan->user->name}'. Denda: Rp ". number_format($request->denda ?? 0, 0, ',', '.'));
        return redirect()->route('admin.returns.index')->with('success', 'Alat berhasil dikembalikan.');
    }
    // edit data pengembalian (misal salah tanggal)
    public function edit($id)
    {
        $loan = Loan::findOrFail($id);
        // memastikah hanya bisa diedit jika statusnya sudah kembali
        {
            if ($loan->status != 'kembali') {
                return back()->with('error', 'Hanya data pengembalian yang sudah diproses yang bisa diedit.');
            }
            return view('admin.returns.edit', compact('loan'));
        }
    }
    // update data pengembalian
    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $request->validate([
            'tanggal_kembali_aktual' => 'required|date'
        ]);
        $loan->update([
            'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual
        ]);
        // catat log aktivitas
        ActivityLog::record('Update Pengembalian', "Mengubah tanggal kembali aktual untuk alat '{$loan->tool->nama_alat}' yang dipinjam oleh '{$loan->user->name}'. Tanggal baru: " . $request->tanggal_kembali_aktual);
        return redirect()->route('admin.returns.index')->with('success', 'Data pengembalian berhasil diupdate.');
    }
    // hapus data pengembalian (misal data ganda)
    public function destroy($id)
    {        $loan = Loan::findOrFail($id);
        // jika data dihapus,apakah stok mau dikurangi lagi?
        // biasanya hapus riwayat tidak memengaruhi stok fisik saat ini,tapi tergantung kebijakan
        // disini kita asumsikan hanya hapus arsip
        $loan->delete();
        return redirect()->route('admin.returns.index')->with('success', 'Data pengembalian berhasil dihapus.');
    }
}
