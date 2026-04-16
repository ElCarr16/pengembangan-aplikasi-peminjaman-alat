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
        ]);

        return DB::transaction(function () use ($request, $id) {
            $loan = Loan::findOrFail($id);
            $tool = Tool::findOrFail($request->tool_id);
            $newStatus = $request->status;
            $oldStatus = $loan->status;

            // 1. Logika Stok (Biarkan seperti aslinya karena sudah benar)
            if ($oldStatus == 'pending' && $newStatus == 'disetujui') {
                if ($tool->stok < $request->jumlah) {
                    return back()->with('error', 'Stok tidak mencukupi.');
                }
                $tool->decrement('stok', $request->jumlah);
            } elseif ($oldStatus == 'disetujui' && $newStatus == 'kembali') {
                $tool->increment('stok', $loan->jumlah);
                $loan->tanggal_kembali_aktual = now();
            } elseif ($oldStatus == 'disetujui' && in_array($newStatus, ['pending', 'ditolak'])) {
                $tool->increment('stok', $loan->jumlah);
                $loan->tanggal_kembali_aktual = null;
            }

            // 2. LOGIKA SAPU JAGAT KODE STRUK (Pindahkan ke sini agar pasti terbaca)
            // Ambil kode yang sudah ada di database sekarang
            $currentCode = $loan->receipt_code;

            // Cek: Jika statusnya DISUETUJUI tapi kodenya masih kosong, BUAT SEKARANG!
            if ($newStatus == 'disetujui' && empty($currentCode)) {
                $currentCode = 'STRK-' . strtoupper(Str::random(5)) . '-' . $loan->id;
            }

            // 3. Eksekusi Update
            $loan->update([
                'user_id'                 => $request->user_id,
                'tool_id'                 => $request->tool_id,
                'jumlah'                  => $request->jumlah,
                'tanggal_pinjam'          => $request->tanggal_pinjam,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'status'                  => $newStatus,
                'tanggal_kembali_aktual'  => $loan->tanggal_kembali_aktual,
                'receipt_code'            => $currentCode // Gunakan variabel $currentCode yang sudah dipastikan isinya
            ]);

            ActivityLog::record('Update Loan', "Mengubah status pinjaman ID: {$loan->id} menjadi {$newStatus}");

            return redirect()->route('admin.loans.index')->with('success', 'Data peminjaman berhasil diperbarui.');
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
