<div style="font-family: Arial, sans-serif; text-align: center;">
    <h2>Halo, {{ $user->name }}!</h2>
    <p>Gunakan kode OTP di bawah ini untuk mereset password Anda:</p>
    <h1 style="background: #f4f4f4; padding: 10px; display: inline-block; letter-spacing: 5px;">{{ $otp }}</h1>
    <p>Kode ini berlaku selama 5 menit.</p>
</div>
