<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent The Tools | Solusi Peminjaman Alat</title>
    <!-- Google Fonts & Bootstrap Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link href="{{ asset('assets/bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel=" stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --warning-color: #ffc107;
            --accent-color: #ffffff;
            --font-main: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--font-main);
            color: #2d3436;
        }

        /* ================= NAVBAR ================= */
        .navbar {
            backdrop-filter: blur(10px);
            background: #ffc107 !important;
            transition: 0.3s;
        }

        /* ================= HERO SECTION ================= */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.4)),
                url("{{ asset('assets/images/background/background.jpg') }}");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: rgb(255, 255, 255);
            padding: 160px 0 120px;
        }

        .hero-title {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.2;
        }

        /* ================= FEATURE CARDS ================= */
        .feature-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }

        .icon-box {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            font-size: 1.5rem;
            margin-bottom: 20px;
            background: rgba(13, 110, 253, 0.1);
            color: var(--warning-color);
        }

        /* ================= TOOL CARDS ================= */
        .tool-card {
            border: none;
            border-radius: 20px;
        }

        .img-wrapper {
            background: #f8f9fa;
            border-radius: 15px;
            margin: 10px;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .img-wrapper img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            transition: 0.5s;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* ================= UTILS ================= */
        .btn-rounded {
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 600;
        }

        @media (max-width: 576px) {
            .hero-section {
                padding: 100px 0 60px;
                border-radius: 0 0 25px 25px;
            }

            .tool-card .card-body {
                padding: 1rem;
            }
        }
    </style>
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center text-dark" href="{{ route('welcome') }}">
                <i class="bi bi-shop me-2 text-dark"></i>RENT THE TOOLS
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="bi bi-list fs-2"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link px-3" href="{{ route('welcome') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item dropdown ms-lg-3 w-100 w-lg-auto mt-2 mt-lg-0">
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning btn-lg dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                    class="bi bi-person-circle text-dark"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2 ">
                                <li><a class="dropdown-item py-2" href="{{ route('login') }}"><i
                                            class="bi bi-box-arrow-in-right me-2"></i>Sign In</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('register') }}"><i
                                            class="bi bi-person-plus me-2"></i>Sign Up</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-section text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 px-4">
                    <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-bold">#1 Peralatan Kerja
                        Pro</span>
                    <h1 class="hero-title mb-3">PINJAM ALAT KERJA<br><span class="text-warning">JADI LEBIH MUDAH</span>
                    </h1>
                    <p class="lead opacity-75 mb-4">Sistem peminjaman perkakas modern untuk kebutuhan proyek Anda.
                        Cepat, transparan, dan stok selalu terupdate.</p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3 mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-warning btn-lg btn-rounded shadow">Cari
                            Alat</a>
                        <a href="#cara-kerja" class="btn btn-outline-light btn-lg btn-rounded">Cara Kerja</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="container my-5 py-lg-4" id="cara-kerja">
        <div class="row g-4 text-center text-md-start">
            <div class="col-md-4">
                <div class="card h-100 feature-card shadow-sm p-4">
                    <div class="icon-box mx-auto mx-md-0">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="fw-bold">Cari Alat</h5>
                    <p class="text-muted small mb-0">Ribuan stok alat tersedia secara real-time. Cukup klik dan pilih
                        sesuai kategori proyek Anda.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 feature-card shadow-sm p-4 text-dark bg-warning">
                    <div class="icon-box mx-auto mx-md-0 bg-white text-dark">
                        <i class="bi bi-file-earmark-plus text-dark"></i>
                    </div>
                    <h5 class="fw-bold">Ajukan Pinjaman</h5>
                    <p class="small mb-0 opacity-75">Proses verifikasi otomatis yang cepat. Pantau status pengajuan
                        langsung dari Profil User Anda.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 feature-card shadow-sm p-4">
                    <div class="icon-box mx-auto mx-md-0"><i class="bi bi-box-seam"></i></div>
                    <h5 class="fw-bold">Pengembalian</h5>
                    <p class="text-muted small mb-0">Alat dikembalikan sesuai tenggat waktu oleh Peminjam Kepada
                        Petugas.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TOOLS LIST -->
    <section class="container mb-5 pb-5">
        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 text-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-end mb-4 px-2">
            <div>
                <h3 class="fw-bold mb-0">Alat Tersedia</h3>
                <p class="text-muted small mb-0">Pilih peralatan terbaik untuk mendukung pekerjaan Anda</p>
            </div>
            <a class="text-warning text-decoration-none fw-bold" href="{{ route('dashboard') }}">
                Semua Alat <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3 g-lg-4">
            @foreach ($tools as $tool)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card tool-card h-100 shadow-sm">
                        <div class="img-wrapper">
                            <img src="{{ $tool->gambar ? asset('storage/' . $tool->gambar) : 'https://via.placeholder.com/300?text=Alat' }}"
                                alt="{{ $tool->nama_alat }}" loading="lazy">
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0 text-truncate" title="{{ $tool->nama_alat }}">
                                    {{ $tool->nama_alat }}
                                </h6>
                            </div>

                            <div class="mb-3">
                                @if ($tool->stok > 0)
                                    <span class="badge-status bg-success-subtle text-success">
                                        <i class="bi bi-check-circle-fill me-1"></i> {{ $tool->stok }} Tersedia
                                    </span>
                                @else
                                    <span class="badge-status bg-danger-subtle text-danger">
                                        <i class="bi bi-x-circle-fill me-1"></i> Stok Habis
                                    </span>
                                @endif
                            </div>

                            @auth
                                <a href="{{ route('peminjam.tools.show', $tool->id) }}"
                                    class="btn btn-warning btn-rounded w-100 shadow-sm btn-sm py-2">
                                    Pinjam Sekarang
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="btn btn-outline-secondary btn-rounded w-100 btn-sm py-2">
                                    Login untuk Pinjam
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- FOOTER -->
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
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Bantuan /
                                FAQ</a></li>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
