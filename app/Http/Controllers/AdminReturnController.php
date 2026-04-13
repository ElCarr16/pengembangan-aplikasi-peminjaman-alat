<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; //mengimpor class Request
use App\Models\Loan;
use App\Models\Tool;
use App\Models\ActivityLog;
use Carbon\Carbon; //untuk perhitungan waktu
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
            'denda'   => 'nullable|integer|min:0'
        ]);

        return DB::transaction(function () use ($request) {

            $loan = Loan::findOrFail($request->loan_id);

            // 1. Pastikan statusnya sedang dipinjam
            if ($loan->status != 'disetujui') {
                return back()->with('error', 'Data peminjaman tidak valid atau sudah dikembalikan.');
            }

            // 2. Ambil data alat terkait
            $tool = Tool::findOrFail($loan->tool_id);

            // 3. Ambil jumlah yang dipinjam
            $quantityToReturn = $loan->jumlah;

            // ==========================================
            // LOGIKA PERHITUNGAN FINAL TOTAL HARGA
            // ==========================================
            $tglPinjam = Carbon::parse($loan->tanggal_pinjam);
            $tglKembaliAktual = now(); // Dihitung s/d hari ini (waktu pengembalian nyata)

            // Hitung selisih hari nyatanya
            $durasiHari = $tglPinjam->diffInDays($tglKembaliAktual);
            if ($durasiHari == 0) {
                $durasiHari = 1; // Minimal hitungan 1 hari
            }

            // Hitung final total harga
            $finalTotalHarga = $tool->harga_perhari * $loan->jumlah * $durasiHari;
            // ==========================================

            // 4. Update status, tanggal, denda, DAN total harga
            $loan->update([
                'status'                 => 'kembali',
                'tanggal_kembali_aktual' => $tglKembaliAktual,
                'denda'                  => $request->denda ?? 0,
                'total_harga'            => $finalTotalHarga // Menyimpan perhitungan final
            ]);

            // 5. Kembalikan stok alat
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

        return DB::transaction(function () use ($request, $loan) {
            $tool = Tool::findOrFail($loan->tool_id);

            // ==========================================
            // LOGIKA HITUNG ULANG JIKA TANGGAL KEMBALI DI-EDIT
            // ==========================================
            $tglPinjam = Carbon::parse($loan->tanggal_pinjam);
            $tglKembaliRevisi = Carbon::parse($request->tanggal_kembali_aktual);

            $durasiHari = $tglPinjam->diffInDays($tglKembaliRevisi);
            if ($durasiHari == 0) {
                $durasiHari = 1;
            }

            $revisiTotalHarga = $tool->harga_perhari * $loan->jumlah * $durasiHari;
            // ==========================================

            $loan->update([
                'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
                'total_harga'            => $revisiTotalHarga // Update harga berdasarkan tanggal baru
            ]);

            ActivityLog::record('Update Pengembalian', "Mengubah tanggal kembali untuk alat '{$loan->tool->nama_alat}' (User: {$loan->user->name}).");

            return redirect()->route('admin.returns.index')->with('success', 'Data pengembalian berhasil diupdate.');
        });
    }

    // Hapus data pengembalian
    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();
        return redirect()->route('admin.returns.index')->with('success', 'Data pengembalian berhasil dihapus.');
    }
}
