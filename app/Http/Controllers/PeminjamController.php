<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\loan;
use App\Models\tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamController extends Controller
{
    public function index()
    {
        $tools = tool::with('category')->get();

        return view('peminjam.dashboard', compact('tools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => ['required', 'integer', 'exists:tools,id'],
            'tanggal_kembali' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $tool = tool::find($request->tool_id);

        if (! $tool) {
            return back()->withErrors(['tool_id' => 'Alat tidak ditemukan.']);
        }

        if ($tool->stok <= 0) {
            return back()->withErrors(['tool_id' => 'Stok alat tidak tersedia.']);
        }

        loan::create([
            'user_id' => Auth::id(),
            'tool_id' => $tool->id,
            'tanggal_pinjam' => now(),
            'tanggal_kembali_rencana' => $request->tanggal_kembali,
            'status' => 'pending',
        ]);

        ActivityLog::record('Tambah Alat', 'Menambahkan alat baru: ' . $tool->nama_alat);

        return back()->with('success', 'Pengajuan berhasil, menunggu persetujuan.');
    }

    public function history()
    {
        $loans = loan::where('user_id', Auth::id())
            ->with('tool')
            ->orderByDesc('created_at')
            ->get();

        return view('peminjam.riwayat', compact('loans'));
    }
}
