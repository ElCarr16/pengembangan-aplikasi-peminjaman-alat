<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamController extends Controller
{
    public function index(Request $request)
    {
        // fitur pencarian
        $query = Tool::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nama_alat', 'like', "%$request->search%")
                ->orWhere('deskripsi', 'like', "%$request->search%");
        }
        
        $tools = $query->paginate(8);
        $categories = Category::all();
        $tools = $query->paginate(8);
        return view('peminjam.dashboard', compact('tools', 'categories'));
    }

    //membuat fungsi filter menggunakan kategori    
    public function category($id)
    {
        $categories = Category::all();
        $tools = Tool::where('category_id', $id)->paginate(8);

        // Pass BOTH tools and categories
        return view('peminjam.dashboard', compact('tools', 'categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => ['required', 'integer', 'exists:tools,id'],
            'tgl_kembali' => ['required', 'date', 'after_or_equal:today'],
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $tool = Tool::findOrFail($request->tool_id);

        if ($tool->stok <= 0) {
            return back()->withErrors(['tool_id' => 'Stok alat tidak tersedia.']);
        }

        if ($request->jumlah > $tool->stok) {
            return back()->withErrors(['jumlah' => 'Jumlah melebihi stok']);
        }

        Loan::create([
            'user_id' => Auth::id(),
            'tool_id' => $tool->id,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => now(),
            'tanggal_kembali_rencana' => $request->tgl_kembali,
            'status' => 'pending',
        ]);

        ActivityLog::record('Peminjaman', 'Mengajukan peminjaman alat: ' . $tool->nama_alat);

        return redirect()->route('peminjam.dashboard')->with(
            'success',
            'Peminjaman telah diajukan! Silahkan cek profil untuk melihat status peminjaman alat,
        dan jika sudah disetujui silahkan ambil dengan menunjukan halaman profil mu kepada petugas di toko.'
        );
    }

    public function requestReturn($id)
    {
        $loan = Loan::findOrFail($id);

        // Pastikan peminjam hanya bisa mengembalikan alatnya sendiri yang sudah diambil
        if ($loan->user_id == Auth::id() && $loan->status == 'disetujui' && $loan->is_diambil) {
            $loan->update([
                'is_return_requested' => true
            ]);

            return back()->with('success', 'Pengembalian telah diajukan! Silahkan serahkan alat ke petugas di toko untuk diverifikasi.');
        }

        return back()->with('error', 'Gagal mengajukan pengembalian.');
    }

    public function history()
    {
        $loans = Loan::where('user_id', Auth::id())
            ->with('tool')
            ->orderByDesc('created_at')
            ->get();

        return view('peminjam.profil', compact('loans'));
    }
}
