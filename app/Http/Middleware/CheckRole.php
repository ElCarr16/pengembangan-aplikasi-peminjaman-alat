<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 1. Cek Kelengkapan Profil khusus Peminjam
        if ($user->role === 'peminjam') {
            if (empty($user->alamat) || empty($user->nomor_telepon) || empty($user->tanggal_lahir)) {

                /**
                 * PERBAIKAN DI SINI:
                 * Kita cek apakah rute saat ini adalah 'peminjam.profile' atau 'peminjam.profile.update'
                 * Jika iya, jangan di-redirect lagi agar tidak looping.
                 */
                if (
                    !$request->routeIs('peminjam.profile') &&
                    !$request->routeIs('peminjam.profile.update') &&
                    !$request->is('logout')
                ) {

                    return redirect()->route('peminjam.profile')
                        ->with('warning', 'Anda wajib melengkapi profil sebelum melanjutkan.');
                }
            }
        }

        // 2. Cek Izin Role
        if (!in_array($user->role, $roles)) {
            abort(403, 'Akses Ditolak');
        }

        return $next($request);
    }
}
