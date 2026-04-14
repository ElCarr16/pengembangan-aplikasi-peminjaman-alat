<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Models\Tool;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminLoanController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi peminjaman.
     */
    public function index()
    {
        $loans = Loan::with(['user', 'tool'])->latest()->paginate(10);
        return view('admin.loans.index', compact('loans'));
    }

    /**
     * Menampilkan form untuk membuat peminjaman baru.
     */
    public function create()
    {
        $users = User::where('role', 'peminjam')->get();
        $tools = Tool::all();

        return view('admin.loans.create', compact('users', 'tools'));
    }

    /**
     * Menyimpan data peminjaman baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dasar
        $request->validate([
            'user_id'                 => 'required|exists:users,id',
            'tool_id'                 => 'required|exists:tools,id',
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'status'                  => 'required|in:pending,disetujui,ditolak,kembali'
        ]);

        // 2. Ambil data alat dulu untuk pengecekan stok
        $tool = Tool::findOrFail($request->tool_id);

        // 3. Proteksi stok (Pencegahan sebelum masuk ke Database Transaction)
        if ($request->jumlah > $tool->stok) {
            return back()
                ->withInput()
                ->with('error', "Gagal! Jumlah pinjam ({$request->jumlah}) melebihi stok yang tersedia ({$tool->stok}).");
        }

        // 4. Proses Simpan dengan Transaction
        return DB::transaction(function () use ($request, $tool) {

            $receiptCode = null;
            if ($request->status == 'disetujui') {
                $receiptCode = 'STRK-ADM-' . strtoupper(Str::random(5)) . '-' . time(); // Unique code for admin-created loans
            }

            // Jika status langsung 'disetujui', kurangi stok
            if ($request->status == 'disetujui') {
                $tool->decrement('stok', $request->jumlah);
            }

            // Simpan data peminjaman
            Loan::create([
                'user_id'                 => $request->user_id,
                'tool_id'                 => $request->tool_id,
                'jumlah'                  => $request->jumlah,
                'tanggal_pinjam'          => $request->tanggal_pinjam,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'status'                  => $request->status,
                'petugas_id'              => Auth::id(),
                'tanggal_kembali_aktual'  => null,
                'receipt_code'            => $receiptCode, // Add receipt code here
            ]);

            ActivityLog::record('Create Loan', 'Admin membuat data pinjaman baru secara manual');

            return redirect()->route('admin.loans.index')->with('success', 'Peminjaman berhasil ditambahkan.');
        });
    }

    /**
     * Menampilkan halaman edit.
     */
    public function edit($id)
    {
        $loan = Loan::findOrFail($id);
        $users = User::where('role', 'peminjam')->get();
        $tools = Tool::all();

        return view('admin.loans.edit', compact('loan', 'users', 'tools'));
    }

    /**
     * Memperbarui data peminjaman.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id'                 => 'required|exists:users,id',
            'tool_id'                 => 'required|exists:tools,id',
            'jumlah'                  => 'required|integer|min:1',
            'status'                  => 'required|in:pending,disetujui,ditolak,kembali',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'bayar' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $loan = Loan::findOrFail($id);
            $tool = Tool::findOrFail($request->tool_id);
            $newStatus = $request->status;

            // Generate receipt code if status becomes 'disetujui' and it doesn't have one
            if ($newStatus == 'disetujui' && empty($loan->receipt_code)) {
                $loan->receipt_code = 'STRK-ADM-' . strtoupper(Str::random(5)) . '-' . $loan->id;
            }

            $total_harga = 0;
            $denda = 0;
            $kembalian = 0;

            // Logika saat status diubah menjadi 'kembali'
            if ($loan->status != 'kembali' && $newStatus == 'kembali') {
                $tool->increment('stok', $loan->jumlah);
                $loan->tanggal_kembali_aktual = now();

                // Hitung Biaya Sewa
                $tglPinjam = \Carbon\Carbon::parse($loan->tanggal_pinjam);
                $tglRencana = \Carbon\Carbon::parse($loan->tanggal_kembali_rencana);
                $tglAktual = \Carbon\Carbon::parse($loan->tanggal_kembali_aktual);

                $durasiSewa = max($tglPinjam->diffInDays($tglRencana), 1);
                $biayaSewaDasar = $tool->harga_perhari * $loan->jumlah * $durasiSewa;

                // Hitung Denda
                if ($tglAktual->gt($tglRencana)) {
                    $hariKeterlambatan = $tglAktual->diffInDays($tglRencana);
                    $denda = $tool->harga_perhari * $loan->jumlah * $hariKeterlambatan;
                }

                $total_harga = $biayaSewaDasar + $denda;

                // Hitung Kembalian
                if ($request->has('bayar')) {
                    $kembalian = $request->bayar - $total_harga;
                }
            }

            // Update Data
            $loan->update([
                'status' => $newStatus,
                'total_harga' => $total_harga > 0 ? $total_harga : $loan->total_harga,
                'denda' => $denda > 0 ? $denda : $loan->denda,
                'bayar' => $request->bayar,
                'kembalian' => $kembalian,
                'receipt_code' => $loan->receipt_code, // Ensure receipt code is updated/preserved
                'tanggal_kembali_aktual' => $loan->tanggal_kembali_aktual,
            ]);

            return redirect()->route('admin.loans.index')->with('success', 'Transaksi selesai.');
        });
    }

    /**
     * Menghapus data peminjaman.
     */
    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $loan = Loan::findOrFail($id);

            if ($loan->status == 'disetujui') {
                $loan->tool->increment('stok', $loan->jumlah);
            }

            $loan->delete();

            ActivityLog::record('Delete Loan', "Menghapus data pinjaman ID: {$id}");

            return redirect()->route('admin.loans.index')->with('success', 'Data peminjaman berhasil dihapus.');
        });
    }
    /**
     * Menampilkan halaman cetak struk
     */
    public function cetakStruk($id)
    {
        $loan = Loan::with(['user', 'tool'])->findOrFail($id);

        // Pastikan struk hanya bisa dilihat jika statusnya disetujui atau kembali
        if (!in_array($loan->status, ['disetujui', 'kembali'])) {
            return abort(403, 'Struk belum tersedia. Pengajuan harus di-ACC terlebih dahulu.');
        }

        return view('admin.loans.struk', compact('loan'));
    }
}
