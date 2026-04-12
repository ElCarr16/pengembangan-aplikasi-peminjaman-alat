@extends('layouts.app')

@section('content')
    <div class="row justify-content-center py-5">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 p-sm-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-person-check-fill text-warning display-4"></i>
                        <h3 class="fw-bold text-dark mt-3">Apakah Ini Akun Anda?</h3>
                        <p class="text-muted small">Kami menemukan akun yang cocok dengan email tersebut</p>
                    </div>

                    <div class="bg-light p-4 rounded-4 mb-4 text-start">
                        <div class="mb-2">
                            <label class="text-muted small d-block">Nama Lengkap</label>
                            <span class="fw-bold text-dark">{{ $user->name }}</span>
                        </div>
                        <div class="mb-2">
                            <label class="text-muted small d-block">Email</label>
                            <span class="fw-bold text-dark">{{ Str::mask($user->email, '*', 3, 10) }}</span>
                        </div>
                        <div>
                            <label class="text-muted small d-block">Nomor Telepon</label>
                            <span class="fw-bold text-dark">{{ Str::mask($user->nomor_telepon, '*', 4, 4) }}</span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <form action="{{ route('password.send_otp') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-warning btn-lg w-100 rounded-pill fw-bold shadow-sm">
                                Ya, Kirim Kode OTP <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        </form>
                        <a href="{{ route('password.request') }}"
                            class="btn btn-outline-secondary btn-lg rounded-pill fw-bold">
                            Bukan, Ini Bukan Akun Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
