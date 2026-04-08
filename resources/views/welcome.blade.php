<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent The Tools</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Style -->
    <style>
        /* ================= HERO SECTION ================= */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                url("{{ asset('assets/images/background/background.jpg') }}");
            background-size: cover;
            background-position: center;
            color: white;
            padding: 120px 20px;
            border-radius: 0 0 20px 20px;
            text-align: center;
        }

        /* RESPONSIVE TEXT HERO */
        .hero-title {
            font-size: 3rem;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
        }

        /* ================= CARD DESTINATION ================= */
        .tool-card img {
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* ================= FEATURE ICON ================= */
        .feature-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        /* card image */
        .tool-card img {
            height: 200px;
            object-fit: contain;
            background-color: #f8f9fa;
            padding: 10px;
        }
    </style>
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">

            <!-- Logo / Brand -->
            <a class="navbar-brand fw-bold" href="{{ route('welcome') }}">RENT THE TOOLS!!</a>

            <!-- Button toggle (mobile) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Navbar -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Masuk
                        </a>
                        <ul class="dropdown-menu">
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Sign In</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Sign Up</a>
                        </ul>
                    </li>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ================= HERO ================= -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">PINJAM ALAT JADI MUDAH</h1>
            <p class="lead">Sistem peminjaman perkakas yang cepat, transparan, dan modern.</p>
            <p></p>
            <div class="mt-4">
                <a href="{{ route('login') }}" class="btn btn-warning btn-lg me-2">Beranda</a>
            </div>
        </div>
    </section>

    <!-- ================= FEATURE SECTION ================= -->
    <section class="container my-5">
        <div class="row text-center">

            <!-- Feature 1 -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="feature-icon">🔍</div>
                        <h5>Cari Alat</h5>
                        <p class="text-muted">Cari dan cek stok alat secara real-time tanpa ribet.</p>
                    </div>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="feature-icon">📄</div>
                        <h5>Ajukan Pinjaman</h5>
                        <p class="text-muted">Ajukan peminjaman langsung dari sistem dengan cepat.</p>
                    </div>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="feature-icon">📦</div>
                        <h5>Pengembalian</h5>
                        <p class="text-muted">Monitoring pengembalian alat agar lebih terstruktur.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ================= TOOLS SECTION ================= -->
    <section class="container mb-5">

        {{-- NOTIFIKASI --}}
        @if (session('error'))
            <div class="alert alert-warning text-center">
                {{ session('error') }}
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Alat Tersedia</h4>
            <a class="btn btn-outline-dark btn-sm" href="{{ route('dashboard') }}">Lihat Semua</a>
        </div>

        <div class="row">

            @foreach ($tools as $tool)
                <div class="col-6 col-md-3 mb-4">

                    <div class="card tool-card border-0 shadow-sm h-100">

                        {{-- GAMBAR --}}
                        <img src="{{ $tool->gambar ? asset('storage/' . $tool->gambar) : 'https://via.placeholder.com/300' }}"
                            class="card-img-top" style="height:200px; object-fit: contain;">

                        <div class="card-body">

                            {{-- NAMA --}}
                            <h6 class="mb-1">{{ $tool->nama_alat }}</h6>

                            {{-- STATUS --}}
                            @if ($tool->stok > 0)
                                <small class="text-success">Tersedia ({{ $tool->stok }})</small>
                            @else
                                <small class="text-danger">Habis</small>
                            @endif

                            {{-- BUTTON --}}
                            <div class="mt-2">
                                @auth
                                    <a href="{{ route('peminjam.tools.show', $tool->id) }}" class="btn btn-primary w-100">
                                        Pinjam Alat
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm w-100">
                                        Pinjam
                                    </a>
                                @endauth
                            </div>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <small>© 2026 Rent The Tools - Laravel App</small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
