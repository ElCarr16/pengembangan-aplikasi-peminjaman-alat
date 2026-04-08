<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tool;
use App\models\category;
use App\Models\loan;
use App\Models\ActivityLog;


class AdminController extends Controller
{
    public function index()
    {
        // mengambil data statistik untuk dashboard admin
        $totalUser = user::count();
        $totalAlat = tool::count();
        $totalStok = tool::sum('stok');
        $totalKategori = category::count();
        // menghitung jumlah peminjaman yang sedang berlangsung (berstatus disetujui)
        $sedangDipinjam = loan::where('status', 'disetujui')->count();
        $sudahDikembalikan = loan::where('status', 'dikembalikan')->count();
        // mengambil 5 aktivitas terakhir
        $recentLogs = activityLog::latest()->take(5)->get();
        return view('admin.dashboard', compact(
            'totalUser', 
            'totalAlat', 
            'totalStok', 
            'totalKategori', 
            'sedangDipinjam', 
            'sudahDikembalikan', 
            'recentLogs'));
    }
}
