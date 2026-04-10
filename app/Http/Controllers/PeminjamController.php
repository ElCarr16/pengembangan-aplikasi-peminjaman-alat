<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Loan;
use App\Models\Tool;
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
        $tools = $query->paginate(10);
        return view('peminjam.dashboard', compact('tools'));

        $tools = tool::with('category')->get();

        return view('peminjam.dashboard', compact('tools'));
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

        return redirect()->route('peminjam.dashboard')
            ->with('success', 'Pengajuan berhasil, menunggu persetujuan.');
    }
    public function history()
    {
        $loans = Loan::where('user_id', Auth::id())
            ->with('tool')
            ->orderByDesc('created_at')
            ->get();

        return view('peminjam.riwayat', compact('loans'));
    }
}
