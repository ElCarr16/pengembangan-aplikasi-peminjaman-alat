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

        // Validasi form dari modal pop-up
        $request->validate([
            'kondisi' => 'required|in:baik,lecet_ringan,lecet_berat,rusak,mati_total,hilang',
            'jumlah_hilang' => 'nullable|integer|min:1|max:' . $loan->jumlah,
            'gambar_return' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'deskripsi_denda' => 'nullable|string|max:1000'
        ]);

        if ($loan->status == 'disetujui' && $loan->is_diambil && $loan->is_return_requested) {

            DB::transaction(function () use ($loan, $request) {
                $tool = $loan->tool;
                $jumlahPinjam = $loan->jumlah;
                // 0. Logika Simpan Foto
                if ($request->hasFile('gambar_return')) {
                    // Simpan file ke storage/app/public/loans/return
                    $path = $request->file('gambar_return')->store('loans/return', 'public');
                    $loan->gambar_return = $path;
                }

                // 1. Logika Alat Hilang & Sisa yang Dikembalikan
                $jumlahHilang = ($request->kondisi == 'hilang') ? $request->jumlah_hilang : 0;
                $jumlahKembali = $jumlahPinjam - $jumlahHilang; // Sesuai permintaanmu: Pinjam - Hilang = Kembali ke stok

                // 2. Logika Hitung Denda Kondisi
                $dendaKondisi = 0;
                switch ($request->kondisi) {
                    case 'lecet_ringan':
                        $dendaKondisi = 25000;
                        break;
                    case 'lecet_berat':
                        $dendaKondisi = 50000;
                        break;
                    case 'rusak':
                        $dendaKondisi = 75000;
                        break;
                    case 'mati_total':
                        $dendaKondisi = 100000;
                        break;
                    case 'hilang':
                        $dendaKondisi = 150000 * $jumlahHilang;
                        break; // Denda dikali jumlah barang hilang
                }

                // 2.5 Logika Hitung Denda Keterlambatan
                $tglKembaliRencanaStart = Carbon::parse($loan->tanggal_kembali_rencana)->startOfDay();
                $tglKembaliAktualReal = now();
                $tglKembaliAktualStart = $tglKembaliAktualReal->copy()->startOfDay();

                $hariTelat = 0;
                $dendaTelat = 0;
                if ($tglKembaliAktualStart->greaterThan($tglKembaliRencanaStart)) {
                    $hariTelat = $tglKembaliRencanaStart->diffInDays($tglKembaliAktualStart);
                    // Biaya denda keterlambatan = Rp 5.000 * jumlah pinjam * jumlah hari telat
                    $dendaTelat = $hariTelat * 5000 * $jumlahPinjam;
                }
                $totalDenda = $dendaKondisi + $dendaTelat;

                // 3. Logika Total Harga Sewa Dasar
                $tglPinjam = Carbon::parse($loan->tanggal_pinjam);
                $durasiHari = max($tglPinjam->diffInDays($tglKembaliAktualReal), 1);
                $totalHargaSewa = $tool->harga_perhari * $jumlahPinjam * $durasiHari;

                // 4. Simpan ke Database
                $deskripsiFinal = $request->filled('deskripsi_denda') ? $request->deskripsi_denda : $request->kondisi;
                if ($hariTelat > 0) {
                    $deskripsiFinal .= " | Terlambat $hariTelat hari (+Rp " . number_format($dendaTelat, 0, ',', '.') . ")";
                }

                $loan->update([
                    'status'                 => 'kembali',
                    'tanggal_kembali_aktual' => $tglKembaliAktualReal,
                    'total_harga'            => $totalHargaSewa,
                    'denda'                  => $totalDenda, // Denda kondisi + Denda keterlambatan
                    'deskripsi_denda'        => $deskripsiFinal,
                    'gambar_return'          => $loan->gambar_return
                ]);

                // 5. Kembalikan stok hanya sebanyak alat yang selamat (tidak hilang)
                if ($jumlahKembali > 0) {
                    $tool->increment('stok', $jumlahKembali);
                }

                // 6. Catat Log Aktivitas agar Admin bisa memantau
                if (class_exists(ActivityLog::class)) {
                    $kondisiText = str_replace('_', ' ', strtoupper($request->kondisi));
                    ActivityLog::record('Verifikasi Pengembalian', "Petugas menerima alat '{$tool->nama_alat}'. Kondisi: {$kondisiText}. Kembali: {$jumlahKembali} unit, Hilang: {$jumlahHilang} unit. Denda: Rp " . number_format($totalDenda, 0, ',', '.'));
                }
            });

            return back()->with('success', "Pengembalian berhasil diproses. Stok telah disesuaikan berdasarkan kondisi barang.");
        }

        return back()->withErrors(['error' => 'Gagal memproses. Pastikan peminjam sudah mengajukan pengembalian.']);
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
}
