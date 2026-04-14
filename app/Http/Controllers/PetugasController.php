<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Tool;
use App\Models\ActivityLog; // Tambahkan ini jika ingin mencatat log
use Carbon\Carbon; // Wajib untuk hitung-hitungan hari
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

        if ($loan->status !== 'pending') {
            return back()->withErrors(['error' => 'Peminjaman tidak valid']);
        }

        if ($loan->tool->stok < $loan->jumlah) {
            return back()->withErrors(['error' => 'Stok alat tidak cukup untuk jumlah yang diminta']);
        }

        // GENERATE KODE STRUK DISINI (Otomatis saat petugas klik ACC)
        $receiptCode = 'STRK-' . strtoupper(\Illuminate\Support\Str::random(5)) . '-' . $loan->id;

        DB::transaction(function () use ($loan, $receiptCode) {
            // Update status dan masukkan receipt_code ke database
            $loan->update([
                'status' => 'disetujui',
                'petugas_id' => Auth::id(),
                'receipt_code' => $receiptCode
            ]);

            $loan->tool->decrement('stok', $loan->jumlah);
        });

        return back()->with('success', 'Peminjaman disetujui. Kode struk otomatis dibuat: ' . $receiptCode);
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

    /**
     * Fungsi yang diupdate besar-besaran untuk menghitung harga & validasi pengembalian
     */
    public function processReturn($id, Request $request)
    {
        $loan = Loan::with('tool', 'user')->findOrFail($id);

        // 1. Validasi Input
        $request->validate([
            'kondisi' => 'required|in:baik,lecet_ringan,lecet_berat,rusak,mati_total,hilang',
            'jumlah_hilang' => 'nullable|integer|min:1|max:' . $loan->jumlah,
            'gambar_return' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'deskripsi_denda' => 'nullable|string|max:1000',
            'bayar' => 'required|numeric|min:0',
        ]);

        // 2. Cek apakah status layak dikembalikan
        // Syarat: Status disetujui DAN alat sudah diambil (is_diambil)
        if ($loan->status == 'disetujui' && $loan->is_diambil) {

            try {
                return DB::transaction(function () use ($loan, $request) {
                    $tool = $loan->tool;
                    $jumlahPinjam = $loan->jumlah;

                    // 3. Simpan Foto
                    $path = $request->file('gambar_return')->store('loans/return', 'public');

                    // 4. Hitung Denda Keterlambatan (Sistem tetap hitung otomatis sebagai pengaman)
                    $tglKembaliRencana = Carbon::parse($loan->tanggal_kembali_rencana)->startOfDay();
                    $tglAktual = now();
                    $tglAktualStart = $tglAktual->copy()->startOfDay();

                    $hariTelat = 0;
                    $dendaTelat = 0;
                    if ($tglAktualStart->greaterThan($tglKembaliRencana)) {
                        $hariTelat = $tglKembaliRencana->diffInDays($tglAktualStart);
                        $dendaTelat = $hariTelat * 5000 * $jumlahPinjam;
                    }

                    // 5. Hitung Harga Sewa Dasar
                    $tglPinjam = Carbon::parse($loan->tanggal_pinjam);
                    $durasiHari = max($tglPinjam->diffInDays($tglAktual), 1);
                    $totalHargaSewa = $tool->harga_perhari * $jumlahPinjam * $durasiHari;

                    // 6. Ambil Total Denda & Grand Total
                    // Kita pakai denda yang dihitung JS di frontend (dikirim lewat request)
                    $totalDenda = $request->total_denda ?? 0;
                    $grandTotal = $totalHargaSewa + $totalDenda;
                    $bayar = $request->bayar;

                    // Validasi Pembayaran
                    if ($bayar < $grandTotal) {
                        return back()->withInput()->withErrors(['error' => 'Uang tunai kurang! Total tagihan: Rp ' . number_format($grandTotal, 0, ',', '.')]);
                    }

                    $kembalian = $bayar - $grandTotal;

                    // 7. Logika Stok
                    $jumlahHilang = ($request->kondisi == 'hilang') ? $request->jumlah_hilang : 0;
                    $jumlahKembali = $jumlahPinjam - $jumlahHilang;

                    // 8. Update Data Peminjaman
                    $loan->update([
                        'status'                 => 'kembali', // BERUBAH KE KEMBALI
                        'tanggal_kembali_aktual' => $tglAktual,
                        'total_harga'            => $totalHargaSewa,
                        'denda'                  => $totalDenda,
                        'deskripsi_denda'        => $request->deskripsi_denda ?? $request->kondisi,
                        'gambar_return'          => $path,
                        'bayar'                  => $bayar,
                        'kembalian'              => $kembalian,
                        'is_return_requested'    => true, // Otomatis set true karena sudah diproses petugas
                    ]);

                    // 9. Tambah stok jika ada alat yang kembali
                    if ($jumlahKembali > 0) {
                        $tool->increment('stok', $jumlahKembali);
                    }

                    return redirect()->back()->with('success', "Kembali Berhasil! Kembalian: Rp " . number_format($kembalian, 0, ',', '.'));
                });
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
            }
        }

        return back()->withErrors(['error' => 'Status peminjaman tidak valid atau alat belum diambil.']);
    }

    public function verifyPickup($id, Request $request)
    {
        $loan = Loan::findOrFail($id);

        $request->validate([
            'gambar_pickup' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048'
        ]);

        if ($loan->status == 'disetujui' && !$loan->is_diambil) {
            $path = $request->file('gambar_pickup')->store('loans/pickup', 'public');

            $loan->update([
                'is_diambil' => true,
                'gambar_pickup' => $path
            ]);

            return back()->with('success', 'Pengambilan alat berhasil diverifikasi beserta bukti foto.');
        }

        return back()->withErrors(['error' => 'Peminjaman tidak valid untuk pengambilan.']);
    }

    public function report(Request $request)
    {
        $loans = Loan::with(['user', 'tool'])
            ->latest()
            ->get();

        return view('petugas.laporan', compact('loans'));
    }


    /**
     * Fungsi untuk mencetak struk peminjaman
     */
    public function printStruk($id)
    {
        // Ambil data peminjaman berserta relasi yang dibutuhkan
        $loan = Loan::with(['user', 'tool'])->findOrFail($id);

        // Cek apakah statusnya sudah selesai ('kembali')
        if ($loan->status !== 'kembali') {
            return back()->withErrors(['error' => 'Struk hanya bisa dicetak untuk peminjaman yang sudah selesai.']);
        }

        // Mengarahkan ke resources/views/admin/loans/struk.blade.php
        return view('admin.loans.struk', compact('loan'));
    }
}
