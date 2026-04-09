<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class AdminReturnController extends Controller
{
    // Menampilkan riwayat pengembalian
    public function index()
    {
        $returns = Loan::with(['user', 'tool'])
            ->where('status', 'kembali')
            ->latest('tanggal_kembali_aktual')
            ->paginate(10);
        return view('admin.returns.index', compact('returns'));
    }

    // Menampilkan daftar alat yang sedang dipinjam
    public function create(Request $request)
    {
        $loanId = $request->query('loan_id');

        $loans = Loan::with(['user', 'tool'])
            ->where('status', 'disetujui')
            ->latest()
            ->get();

        return view('admin.returns.create', compact('loans', 'loanId'));
    }

    // Proses pengembalian alat
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'denda' => 'nullable|integer|min:0'
        ]);

        // Gunakan DB Transaction agar jika satu proses gagal, semua dibatalkan (mencegah stok kacau)
        return DB::transaction(function () use ($request) {

            $loan = Loan::findOrFail($request->loan_id);

            // 1. Pastikan statusnya sedang dipinjam
            if ($loan->status != 'disetujui') {
                return back()->with('error', 'Data peminjaman tidak valid atau sudah dikembalikan.');
            }

            // 2. Ambil data alat terkait
            $tool = Tool::findOrFail($loan->tool_id);

            // 3. Ambil jumlah yang dipinjam (FIXED: Sesuaikan nama kolom dengan tabel loans Anda, misal 'jumlah')
            // Di kode Anda sebelumnya, Anda memanggil variabel $borrowing yang tidak ada.
            $quantityToReturn = $loan->jumlah;

            // 4. Update status & tanggal peminjaman
            $loan->update([
                'status' => 'kembali',
                'tanggal_kembali_aktual' => now(),
                'denda' => $request->denda ?? 0
            ]);

            // 5. Kembalikan stok alat (FIXED: Gunakan jumlah dari transaksi, hapus increment manual +1)
            $tool->increment('stok', $quantityToReturn);

            // 6. Catat log aktivitas
            ActivityLog::record('Pengembalian Alat', "Alat '{$tool->nama_alat}' dikembalikan oleh '{$loan->user->name}'. Jumlah: $quantityToReturn, Denda: Rp " . number_format($request->denda ?? 0, 0, ',', '.'));

            return redirect()->route('admin.returns.index')->with('success', "Alat berhasil dikembalikan. Stok bertambah $quantityToReturn.");
        });
    }

    // Edit data pengembalian
    public function edit($id)
    {
        $loan = Loan::findOrFail($id);
        if ($loan->status != 'kembali') {
            return back()->with('error', 'Hanya data pengembalian yang sudah diproses yang bisa diedit.');
        }
        return view('admin.returns.edit', compact('loan'));
    }

    // Update data pengembalian
    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $request->validate([
            'tanggal_kembali_aktual' => 'required|date'
        ]);

        $loan->update([
            'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual
        ]);

        ActivityLog::record('Update Pengembalian', "Mengubah tanggal kembali untuk alat '{$loan->tool->nama_alat}' (User: {$loan->user->name}).");

        return redirect()->route('admin.returns.index')->with('success', 'Data pengembalian berhasil diupdate.');
    }

    // Hapus data pengembalian
    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();
        return redirect()->route('admin.returns.index')->with('success', 'Data pengembalian berhasil dihapus.');
    }
}
