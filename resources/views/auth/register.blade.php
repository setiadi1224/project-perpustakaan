<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar — Perpustakaan</title>
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
    width: 900px;
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
    width: 38%;
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
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    bottom: -70px; left: -70px;
  }

  .panel-left::after {
    content: '';
    position: absolute;
    width: 130px; height: 130px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    top: 20px; right: -40px;
  }

  .panel-left .icon-book {
    width: 52px; height: 52px;
    background: rgba(255,255,255,0.15);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 28px;
  }

  .panel-left h1 {
    font-size: 24px;
    font-weight: 700;
    color: #fff;
    line-height: 1.3;
    margin-bottom: 14px;
  }

  .panel-left p {
    font-size: 13px;
    color: rgba(255,255,255,0.72);
    line-height: 1.6;
  }

  .panel-left .badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.15);
    border-radius: 20px;
    padding: 6px 12px;
    font-size: 12px;
    color: #fff;
    margin-top: 24px;
    width: fit-content;
  }

  /* RIGHT PANEL */
  .panel-right {
    background: #fff;
    flex: 1;
    padding: 40px 40px;
    overflow-y: auto;
  }

  .panel-right h2 {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
  }

  .panel-right .subtitle {
    font-size: 13px;
    color: #6B7280;
    margin-bottom: 24px;
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }

  .form-group {
    margin-bottom: 14px;
  }

  .form-group.full { grid-column: 1 / -1; }

  .form-group label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }

  .form-group input,
  .form-group textarea {
    width: 100%;
    border: 1.5px solid #E5E7EB;
    border-radius: 10px;
    padding: 0 14px;
    font-family: inherit;
    font-size: 13.5px;
    color: #111827;
    background: #F9FAFB;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    outline: none;
  }

  .form-group input { height: 40px; }

  .form-group textarea {
    padding: 10px 14px;
    height: 76px;
    resize: none;
    line-height: 1.5;
  }

  .form-group input:focus,
  .form-group textarea:focus {
    border-color: #2563EB;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
  }

  .form-group input.is-invalid,
  .form-group textarea.is-invalid {
    border-color: #EF4444;
    background: #FFF5F5;
  }

  .invalid-feedback {
    font-size: 11.5px;
    color: #EF4444;
    margin-top: 3px;
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
    margin-top: 4px;
  }

  .btn-primary:hover { background: #1D4ED8; }
  .btn-primary:active { transform: scale(0.98); }

  .login-link {
    text-align: center;
    font-size: 13px;
    color: #6B7280;
    margin-top: 14px;
  }

  .login-link a {
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

  .section-divider {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #9CA3AF;
    margin: 6px 0 14px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .section-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #F3F4F6;
  }

  @media (max-width: 640px) {
    .panel-left { display: none; }
    .panel-right { padding: 28px 20px; }
    .form-row { grid-template-columns: 1fr; }
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
    <h1>Bergabung Dengan Kami 🎉</h1>
    <p>Daftar dan nikmati akses ke ribuan koleksi buku, e-book, dan layanan perpustakaan digital.</p>
    <div class="badge">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24">
        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      Role: Anggota / User
    </div>
  </div>

  <!-- RIGHT -->
  <div class="panel-right">
    <h2>Buat Akun Baru</h2>
    <p class="subtitle">Isi formulir berikut untuk membuat akun Anda</p>

    @if ($errors->any())
      <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div class="section-divider">Informasi Pribadi</div>

      <div class="form-row">
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" name="name" value="{{ old('name') }}"
                 placeholder="Nama lengkap Anda"
                 class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label>No. Telepon</label>
          <input type="text" name="no_telepon" value="{{ old('no_telepon') }}"
                 placeholder="08xxxxxxxxxx"
                 class="{{ $errors->has('no_telepon') ? 'is-invalid' : '' }}">
          @error('no_telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group full">
          <label>Email</label>
          <input type="email" name="email" value="{{ old('email') }}"
                 placeholder="contoh@email.com"
                 class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
          @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group full">
          <label>Alamat</label>
          <textarea name="alamat" placeholder="Alamat lengkap Anda"
                    class="{{ $errors->has('alamat') ? 'is-invalid' : '' }}">{{ old('alamat') }}</textarea>
          @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="section-divider">Keamanan Akun</div>

      <div class="form-row">
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password"
                 placeholder="Minimal 6 karakter"
                 class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label>Konfirmasi Password</label>
          <input type="password" name="password_confirmation"
                 placeholder="Ulangi password"
                 class="{{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
          @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <button type="submit" class="btn-primary">Buat Akun Sekarang</button>
    </form>

    <p class="login-link">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
  </div>
</div>

</body>
</html>
