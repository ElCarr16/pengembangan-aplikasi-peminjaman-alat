<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    public function index()
    {
        return view('petugas.dashboard', [
            'loans' => Loan::with(['user', 'tool'])
                ->where('status', 'pending')
                ->latest()
                ->get(),

            'activeLoans' => Loan::with(['user', 'tool'])
                ->where('status', 'disetujui')
                ->latest()
                ->get(),

            'returnedLoans' => Loan::with(['user', 'tool'])
                ->where('status', 'kembali')
                ->latest()
                ->get(),
        ]);
    }

    public function approve($id)
    {
        $loan = Loan::with('tool')->findOrFail($id);

        // validasi status
        if ($loan->status !== 'pending') {
            return back()->withErrors(['error' => 'Peminjaman tidak valid']);
        }

        // cek stok
        if ($loan->tool->stok <= 0) {
            return back()->withErrors(['error' => 'Stok alat habis']);
        }

        DB::transaction(function () use ($loan) {
            $loan->update([
                'status' => 'disetujui',
                'petugas_id' => Auth::id()
            ]);

            $loan->tool->decrement('stok');
        });

        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function reject($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return back()->withErrors(['error' => 'Peminjaman tidak bisa ditolak']);
        }

        $loan->update([
            'status' => 'ditolak',
            'petugas_id' => Auth::id()
        ]);

        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function processReturn($id)
    {
        $loan = Loan::with('tool')->findOrFail($id);

        if ($loan->status !== 'disetujui') {
            return back()->withErrors(['error' => 'Data tidak valid untuk dikembalikan']);
        }

        DB::transaction(function () use ($loan) {
            $loan->update([
                'status' => 'kembali',
                'tanggal_kembali_aktual' => now()
            ]);

            $loan->tool->increment('stok');
        });

        return back()->with('success', 'Alat telah dikembalikan.');
    }

    public function report(Request $request)
    {
        $loans = Loan::with(['user', 'tool'])
            ->latest()
            ->get();

        return view('petugas.laporan', compact('loans'));
    }
}