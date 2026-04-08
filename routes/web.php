<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, AdminController, AdminLoanController, AdminReturnController,
    CategoryController, PetugasController, PeminjamController, ToolController,
    UserController, HomeController, LogController
};

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'home'])->name('welcome');

Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('/register', 'showRegisterForm')->name('register');
        Route::post('/register', 'register');
    });
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Redirector
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            default   => redirect()->route('peminjam.dashboard'),
        };
    })->name('dashboard');

    // ADMIN GROUP
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'role:admin'], function () {
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

    // PETUGAS GROUP
    Route::group([
        'prefix' => 'petugas',
        'as' => 'petugas.',
        'middleware' => 'role:petugas'
    ], function () {

        Route::get('/dashboard', [PetugasController::class, 'index'])->name('dashboard');
        Route::get('/laporan', [PetugasController::class, 'report'])->name('laporan');

        Route::post('/approve/{id}', [PetugasController::class, 'approve'])->name('approve');
        Route::post('/reject/{id}', [PetugasController::class, 'reject'])->name('reject'); // ✅ TAMBAH INI
        Route::post('/return/{id}', [PetugasController::class, 'processReturn'])->name('return');
    });

    // PEMINJAM GROUP
    Route::group(['prefix' => 'peminjam', 'as' => 'peminjam.', 'middleware' => 'role:peminjam'], function () {
        Route::get('/dashboard', [PeminjamController::class, 'index'])->name('dashboard');
        Route::get('/riwayat', [PeminjamController::class, 'history'])->name('riwayat');
        Route::post('/ajukan', [PeminjamController::class, 'store'])->name('ajukan');
    });
});