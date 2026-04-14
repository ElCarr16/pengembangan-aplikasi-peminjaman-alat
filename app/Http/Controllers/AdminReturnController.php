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
            'denda'   => 'nullable|integer|min:0',
            'bayar'   => 'required|integer|min:0', // Tambahkan validasi bayar
            'deskripsi_denda' => 'nullable|string|max:1000'
        ]);

        return DB::transaction(function () use ($request) {
            $loan = Loan::findOrFail($request->loan_id);

            if ($loan->status != 'disetujui') {
                return back()->with('error', 'Data peminjaman tidak valid atau sudah dikembalikan.');
            }

            $tool = Tool::findOrFail($loan->tool_id);
            $tglPinjam = Carbon::parse($loan->tanggal_pinjam);
            $tglKembaliAktual = now();

            // --- 1. Hitung Harga Sewa ---
            $durasiHari = max($tglPinjam->diffInDays($tglKembaliAktual), 1);
            $finalTotalHarga = $tool->harga_perhari * $loan->jumlah * $durasiHari;

            // --- 2. Hitung Denda Otomatis ---
            $tglKembaliRencanaStart = Carbon::parse($loan->tanggal_kembali_rencana)->startOfDay();
            $tglKembaliAktualStart = $tglKembaliAktual->copy()->startOfDay();

            $hariTelat = 0;
            $dendaTelat = 0;
            if ($tglKembaliAktualStart->greaterThan($tglKembaliRencanaStart)) {
                $hariTelat = $tglKembaliRencanaStart->diffInDays($tglKembaliAktualStart);
                $dendaTelat = $hariTelat * 5000 * $loan->jumlah; // Contoh: 5rb per alat per hari
            }

            $inputDenda = $request->denda ?? 0;
            $totalDenda = $inputDenda + $dendaTelat;

            // --- 3. Hitung Kembalian ---
            $grandTotal = $finalTotalHarga + $totalDenda;
            $bayar = $request->bayar;

            if ($bayar < $grandTotal) {
                return back()->withInput()->with('error', 'Uang bayar kurang! Total tagihan: Rp ' . number_format($grandTotal, 0, ',', '.'));
            }

            $kembalian = $bayar - $grandTotal;

            // --- 4. Deskripsi Denda ---
            $deskripsiFinal = $request->deskripsi_denda;
            if ($hariTelat > 0) {
                $tambahanDesc = "Terlambat $hariTelat hari (+Rp " . number_format($dendaTelat, 0, ',', '.') . ")";
                $deskripsiFinal = $deskripsiFinal ? $deskripsiFinal . " | " . $tambahanDesc : $tambahanDesc;
            }

            // --- 5. Update Database ---
            $loan->update([
                'status'                 => 'kembali',
                'tanggal_kembali_aktual' => $tglKembaliAktual,
                'denda'                  => $totalDenda,
                'deskripsi_denda'        => $deskripsiFinal,
                'total_harga'            => $finalTotalHarga,
                'bayar'                  => $bayar,
                'kembalian'              => $kembalian,
            ]);

            $tool->increment('stok', $loan->jumlah);

            ActivityLog::record('Pengembalian Alat', "Alat '{$tool->nama_alat}' dikembalikan. Total: Rp " . number_format($grandTotal, 0, ',', '.') . " | Bayar: Rp " . number_format($bayar, 0, ',', '.') . " | Kembali: Rp " . number_format($kembalian, 0, ',', '.'));

            return redirect()->route('loans.struk')->with('success', "Alat berhasil dikembalikan. Kembalian: Rp " . number_format($kembalian, 0, ',', '.'));
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
            'tanggal_kembali_aktual' => 'required|date',
            'bayar' => 'required|integer|min:0'
        ]);

        return DB::transaction(function () use ($request, $loan) {
            $tool = Tool::findOrFail($loan->tool_id);

            $tglPinjam = Carbon::parse($loan->tanggal_pinjam);
            $tglRevisi = Carbon::parse($request->tanggal_kembali_aktual);

            // Hitung ulang harga sewa
            $durasiHari = max($tglPinjam->diffInDays($tglRevisi), 1);
            $revisiHargaSewa = $tool->harga_perhari * $loan->jumlah * $durasiHari;

            // Hitung ulang kembalian (Denda dianggap tetap atau ambil dari database)
            $grandTotalBaru = $revisiHargaSewa + $loan->denda;
            $kembalianBaru = $request->bayar - $grandTotalBaru;

            if ($request->bayar < $grandTotalBaru) {
                return back()->with('error', 'Uang bayar tidak cukup untuk total revisi Rp ' . number_format($grandTotalBaru, 0, ',', '.'));
            }

            $loan->update([
                'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
                'total_harga'            => $revisiHargaSewa,
                'bayar'                  => $request->bayar,
                'kembalian'              => $kembalianBaru
            ]);

            return redirect()->route('admin.returns.index')->with('success', 'Data pengembalian berhasil diperbarui.');
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
