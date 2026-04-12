<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tool;
use App\Models\Category;
use App\Models\Loan;
use App\Models\ActivityLog;

class AdminController extends Controller
{
    public function index()
    {
        // mengambil data statistik untuk dashboard admin
        $totalUser = User::count();
        $totalAlat = Tool::count();
        $totalStok = Tool::sum('stok');
        $totalKategori = Category::count();
        
        // Menghitung total pendapatan (hanya dari transaksi yang statusnya 'kembali')
        $pendapatanSewa = Loan::where('status', 'kembali')->sum('total_harga');
        $pendapatanDenda = Loan::where('status', 'kembali')->sum('denda');
        $totalPendapatan = $pendapatanSewa + $pendapatanDenda;
        
        // Menghitung jumlah peminjaman yang sedang berlangsung (berstatus disetujui)
        $sedangDipinjam = Loan::where('status', 'disetujui')->count();
        
        // PERBAIKAN: Ubah 'dikembalikan' menjadi 'kembali'
        $sudahDikembalikan = Loan::where('status', 'kembali')->count();
        
        // mengambil 5 aktivitas terakhir
        $recentLogs = ActivityLog::latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalUser',
            'totalAlat',
            'totalStok',
            'totalKategori',
            'sedangDipinjam',
            'sudahDikembalikan',
            'recentLogs',
            'totalPendapatan'
        ));
    }
}