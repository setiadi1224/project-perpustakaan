<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Perpustakaan</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #F0F2F5;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
  }

  .card {
    display: flex;
    width: 820px;
    max-width: 100%;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.12);
    animation: fadeUp 0.5s ease both;
  }

  @keyframes fadeUp {
    from { opacity:0; transform:translateY(24px); }
    to   { opacity:1; transform:translateY(0); }
  }

  /* LEFT PANEL */
  .panel-left {
    background: linear-gradient(145deg, #2563EB 0%, #1D4ED8 60%, #1E40AF 100%);
    width: 42%;
    padding: 48px 36px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    overflow: hidden;
  }

  .panel-left::before {
    content: '';
    position: absolute;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    bottom: -60px; left: -60px;
  }

  .panel-left::after {
    content: '';
    position: absolute;
    width: 120px; height: 120px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    top: 30px; right: -30px;
  }

  .panel-left .icon-book {
    width: 52px; height: 52px;
    background: rgba(255,255,255,0.15);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 28px;
    backdrop-filter: blur(4px);
  }

  .panel-left h1 {
    font-size: 26px;
    font-weight: 700;
    color: #fff;
    line-height: 1.3;
    margin-bottom: 14px;
  }

  .panel-left p {
    font-size: 13.5px;
    color: rgba(255,255,255,0.72);
    line-height: 1.6;
  }

  /* RIGHT PANEL */
  .panel-right {
    background: #fff;
    flex: 1;
    padding: 48px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .panel-right h2 {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 6px;
  }

  .panel-right .subtitle {
    font-size: 13px;
    color: #6B7280;
    margin-bottom: 28px;
  }

  .form-group {
    margin-bottom: 16px;
  }

  .form-group label {
    display: block;
    font-size: 12.5px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }

  .form-group input {
    width: 100%;
    height: 42px;
    border: 1.5px solid #E5E7EB;
    border-radius: 10px;
    padding: 0 14px;
    font-family: inherit;
    font-size: 14px;
    color: #111827;
    background: #F9FAFB;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    outline: none;
  }

  .form-group input:focus {
    border-color: #2563EB;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
  }

  .form-group input.is-invalid {
    border-color: #EF4444;
    background: #FFF5F5;
  }

  .invalid-feedback {
    font-size: 12px;
    color: #EF4444;
    margin-top: 4px;
  }

  .forgot {
    text-align: right;
    margin-top: -8px;
    margin-bottom: 20px;
  }

  .forgot a {
    font-size: 12px;
    color: #2563EB;
    text-decoration: none;
  }

  .btn-primary {
    width: 100%;
    height: 44px;
    background: #2563EB;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s, transform 0.1s;
    letter-spacing: 0.01em;
  }

  .btn-primary:hover { background: #1D4ED8; }
  .btn-primary:active { transform: scale(0.98); }

  .divider {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 18px 0;
  }

  .divider span {
    font-size: 12px;
    color: #9CA3AF;
    white-space: nowrap;
  }

  .divider::before, .divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #E5E7EB;
  }

  .register-link {
    text-align: center;
    font-size: 13px;
    color: #6B7280;
  }

  .register-link a {
    color: #2563EB;
    font-weight: 600;
    text-decoration: none;
  }

  .alert-error {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 13px;
    color: #DC2626;
    margin-bottom: 16px;
  }

  .remember-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
  }

  .remember-row input[type=checkbox] {
    width: 15px; height: 15px;
    accent-color: #2563EB;
    cursor: pointer;
  }

  .remember-row label {
    font-size: 13px;
    color: #6B7280;
    cursor: pointer;
  }

  @media (max-width: 600px) {
    .panel-left { display: none; }
    .panel-right { padding: 32px 24px; }
  }
</style>
</head>
<body>

<div class="card">
  <!-- LEFT -->
  <div class="panel-left">
    <div class="icon-book">
      <svg width="26" height="26" fill="none" viewBox="0 0 24 24">
        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
    <h1>Selamat Datang Kembali 👋</h1>
    <p>Masuk ke sistem perpustakaan untuk mengakses koleksi buku, peminjaman, dan layanan lainnya.</p>
  </div>

  <!-- RIGHT -->
  <div class="panel-right">
    <h2>Login Akun</h2>
    <p class="subtitle">Masukkan kredensial Anda untuk melanjutkan</p>

    @if ($errors->any())
      <div class="alert-error">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}"
               placeholder="contoh@email.com"
               class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password"
               placeholder="Masukkan password"
               class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="remember-row">
        <input type="checkbox" name="remember" id="remember">
        <label for="remember">Ingat saya</label>
      </div>

      <button type="submit" class="btn-primary">Masuk</button>
    </form>

    <div class="divider"><span>atau</span></div>

    <p class="register-link">
      Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
    </p>
  </div>
</div>

</body>
</html>
