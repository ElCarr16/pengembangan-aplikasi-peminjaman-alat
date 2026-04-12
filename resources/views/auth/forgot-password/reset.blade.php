@extends('layouts.app')

@section('content')
    <div class="row justify-content-center py-5">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark">Password Baru</h3>
                        <p class="text-muted small">Buat password yang kuat untuk mengamankan akun Anda</p>
                    </div>

                    <form action="{{ route('password.reset') }}" method="POST">
                        @csrf
                        <!-- Password Baru -->
                        <div class="form-floating mb-3">
                            <input type="password" name="password"
                                class="form-control rounded-3 @error('password') is-invalid @enderror" id="newPass"
                                placeholder="Password Baru" required>
                            <label for="newPass">Password Baru</label>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="form-floating mb-4">
                            <input type="password" name="password_confirmation" class="form-control rounded-3"
                                id="confPass" placeholder="Ulangi Password" required>
                            <label for="confPass">Ulangi Password</label>
                        </div>

                        <button class="w-100 btn btn-lg btn-warning rounded-pill shadow-sm fw-bold py-3" type="submit">
                            Ganti Password <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
