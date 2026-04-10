<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent The Tools</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="{{ asset('assets/bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.8rem 0;
        }

        .navbar-brand {
            font-weight: 800;
            color: #0d6efd !important;
            letter-spacing: -0.5px;
        }

        .main-content {
            flex: 1;
            padding: 2rem 0;
        }

        .content-card {
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .content-card {
                padding: 1.25rem;
                border-radius: 0;
                border: none;
                border-bottom: 1px solid #e2e8f0;
            }

            .main-content {
                padding: 0;
            }
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
            font-weight: 500;
        }
    </style>
</head>

<body>
    {{-- navbar start --}}
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('welcome') }}">
                <i class="bi bi-hammer me-2"></i>Rent The Tools
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="bi bi-list fs-2"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    @auth
                        @if (auth()->user()->role == 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Kelola</a>
                                <ul class="dropdown-menu border-0 shadow-sm">
                                    <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}">Kategori</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('admin.tools.index') }}">Alat</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">User</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('admin.loans.index') }}">Peminjaman</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.returns.index') }}">Pengembalian</a>
                                    </li>
                                </ul>
                            </li>
                        @elseif(auth()->user()->role == 'petugas')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('petugas.dashboard') }}">Dashboard</a>
                            </li>
                        @elseif(auth()->user()->role == 'peminjam')
                            <li class="nav-item"><a class="nav-link" href="{{ route('peminjam.dashboard') }}">Daftar
                                    Alat</a></li>
                        @endif

                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn btn-outline-primary px-4 rounded-pill" href="{{ route('login') }}">Masuk</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    {{-- navbar end --}}

    <main class="main-content">
        <div class="container">
            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="alert alert-success shadow-sm mb-4">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger shadow-sm mb-4">
                    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Area Content --}}
            <div class="content-card">
                @yield('content')
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white pt-5 pb-4 mt-auto">
        <div class="container text-center text-md-start">
            <div class="row gy-4">
                <div class="col-md-6">
                    <h5 class="fw-bold text-warning mb-3">RENT THE TOOLS</h5>
                    <p class="small opacity-50 pe-md-5">Platform peminjaman alat terpercaya yang memudahkan teknisi,
                        tukang, dan hobiis mendapatkan peralatan berkualitas tanpa biaya beli mahal.</p>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold mb-3">Link Cepat</h6>
                    <ul class="list-unstyled small opacity-75">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Ketentuan
                                Layanan</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Kebijakan
                                Privasi</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Bantuan / FAQ</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold mb-3">Ikuti Kami</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4 opacity-25">
            <div class="text-center">
                <small class="opacity-50">© 2026 Rent The Tools. Dibuat dengan <i
                        class="bi bi-heart-fill text-danger mx-1"></i> untuk efisiensi kerja.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
