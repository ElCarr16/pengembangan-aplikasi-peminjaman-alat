<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tool;
use App\Models\Category;
use App\Models\Loans;
use App\Models\ActivityLog;

class AdminController extends Controller
{
    //mengambil data statistik untuk kartu dashboard
    public function index()
    {
        $totalUser = User::count();
        $totalAlat = Tool::count();
        $totalstok = Tool::sum('stok');
        $totalKategori = Category::count();
        // menghitung peminjaman yang sedang berlangsung (status disetujui)
        $sedangDipinjam = Loans::where('status', 'disetujui')->count();
        // menghitung peminjaman yang sudah dikembalikan (status dikembalikan)
        $sudahDikembalikan = Loans::where('status', 'kembali')->count();
        // mengambil 5 aktivitas terbaru
        $recentLogs = ActivityLog::with('user')->latest()->take(5)->get();
        // mengirim data ke view
        return view('admin.dashboard',compact
        (
            'totalUser',
            'totalAlat',
            'totalStok',
            'totalKategori',
            'sedangDipinjam',
            'sudahDikembalikan',
            'recentLogs'
        ));
    }
}
