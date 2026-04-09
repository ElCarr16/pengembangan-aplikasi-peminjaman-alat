<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    AuthController,
    AdminController,
    AdminLoanController,
    AdminReturnController,
    CategoryController,
    PetugasController,
    PeminjamController,
    ToolController,
    UserController,
    HomeController,
    LogController
};

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'home'])->name('welcome');

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /**
     * Centralized Dashboard Redirector
     * Mengarahkan user ke dashboard masing-masing berdasarkan role.
     */
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return match ($user->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            'peminjam' => redirect()->route('peminjam.dashboard'),
            default   => abort(403, 'Role tidak dikenali.'),
        };
    })->name('dashboard');

    /**
     * ADMIN ROUTES
     */
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/logs', [LogController::class, 'index'])->name('logs');

        Route::resources([
            'users'      => UserController::class,
            'tools'      => ToolController::class,
            'categories' => CategoryController::class,
            'loans'      => AdminLoanController::class,
            'returns'    => AdminReturnController::class,
        ]);
    });

    /**
     * PETUGAS ROUTES
     */
    Route::prefix('petugas')->name('petugas.')->middleware('role:petugas')->group(function () {
        Route::get('/dashboard', [PetugasController::class, 'index'])->name('dashboard');
        Route::get('/laporan', [PetugasController::class, 'report'])->name('laporan');

        // Approval & Returns
        Route::controller(PetugasController::class)->group(function () {
            Route::post('/approve/{id}', 'approve')->name('approve');
            Route::post('/reject/{id}', 'reject')->name('reject');
            Route::post('/return/{id}', 'processReturn')->name('return');
        });
    });

    /**
     * PEMINJAM ROUTES
     */
    Route::prefix('peminjam')
        ->name('peminjam.')
        ->middleware('role:peminjam')
        ->group(function () {
            Route::get('/dashboard', [PeminjamController::class, 'index'])->name('dashboard');
            Route::get('/riwayat', [PeminjamController::class, 'history'])->name('riwayat');
            Route::post('/ajukan', [PeminjamController::class, 'store'])->name('ajukan');
            Route::get('/tools/{id}', [ToolController::class, 'show'])->name('tools.show');
            Route::post('/ajukan', [PeminjamController::class, 'store'])->name('ajukan');
            Route::get('/profile', [UserController::class, 'editProfile'])->name('profile');
            Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
        });
});
