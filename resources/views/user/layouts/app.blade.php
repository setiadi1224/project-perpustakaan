<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Perpustakaan Digital')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #E8EAF0;
    min-height: 100vh;
    display: flex;
}

/* ══ SIDEBAR ══════════════════════════════════════════ */
.sidebar {
    width: 150px;
    min-height: 100vh;
    background: #0F172A;
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0; left: 0;
    z-index: 50;
}

.sb-brand {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 20px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
}

.sb-brand .brand-icon {
    width: 28px; height: 28px;
    background: #2563EB;
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

.sb-brand span {
    font-size: 13px;
    font-weight: 600;
    color: #fff;
}

.sb-nav {
    padding: 14px 10px;
    display: flex;
    flex-direction: column;
    gap: 2px;
    flex: 1;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 9px 10px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 500;
    color: rgba(255,255,255,0.45);
    text-decoration: none;
    transition: all 0.15s;
    border: none;
    background: none;
    cursor: pointer;
    font-family: inherit;
    width: 100%;
    text-align: left;
}

.nav-item:hover {
    background: rgba(255,255,255,0.07);
    color: rgba(255,255,255,0.8);
}

.nav-item.active {
    background: #2563EB;
    color: #fff;
}

.nav-item.logout { color: rgba(255,100,100,0.65); }
.nav-item.logout:hover {
    background: rgba(255,80,80,0.1);
    color: #ff6b6b;
}

.sb-bottom {
    padding: 10px;
    border-top: 1px solid rgba(255,255,255,0.07);
}

/* ══ MAIN ═════════════════════════════════════════════ */
.main {
    margin-left: 150px;
    flex: 1;
    padding: 22px 24px;
    min-height: 100vh;
}

/* ══ TOPBAR ═══════════════════════════════════════════ */
.topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.search-wrap {
    position: relative;
    width: 240px;
}

.search-wrap svg {
    position: absolute;
    left: 10px; top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}

.search-wrap input {
    width: 100%;
    height: 38px;
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    padding: 0 14px 0 34px;
    font-family: inherit;
    font-size: 13px;
    color: #111827;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.search-wrap input:focus {
    border-color: #2563EB;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

.search-wrap input::placeholder { color: #9CA3AF; }

.user-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}

.avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: #2563EB;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
    font-weight: 700;
    color: #fff;
}

@media (max-width: 580px) {
    .sidebar  { width: 56px; }
    .sb-brand span, .nav-item span { display: none; }
    .main     { margin-left: 56px; }
}
</style>

@stack('styles')
</head>
<body>

{{-- ══ SIDEBAR ══ --}}
<aside class="sidebar">
    <div class="sb-brand">
        <div class="brand-icon">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24">
                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <span>Anggota</span>
    </div>

    <nav class="sb-nav">
        <a href="{{ route('user.home') }}"
           class="nav-item {{ request()->routeIs('user.home') ? 'active' : '' }}">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path d="M3 9.75L12 3l9 6.75V20a1 1 0 01-1 1H4a1 1 0 01-1-1V9.75z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
            <span>Home</span>
        </a>
        <a href="{{ route('user.kategori') }}"
           class="nav-item {{ request()->routeIs('user.kategori') ? 'active' : '' }}">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            <span>Category</span>
        </a>
        <a href="{{ route('user.library') }}"
           class="nav-item {{ request()->routeIs('user.library') ? 'active' : '' }}">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            <span>Library</span>
        </a>
        <a href="{{ route('user.riwayat') }}"
           class="nav-item {{ request()->routeIs('user.riwayat') ? 'active' : '' }}">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            <span>Riwayat</span>
        </a>
        <a href="{{ route('user.denda') }}"
           class="nav-item {{ request()->routeIs('user.denda') ? 'active' : '' }}">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            <span>Denda</span>
        </a>
        <a href="{{ route('user.profile') }}"
           class="nav-item {{ request()->routeIs('user.profile') ? 'active' : '' }}">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            <span>Profile</span>
        </a>
    </nav>

    <div class="sb-bottom">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item logout">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>

{{-- ══ MAIN ══ --}}
<main class="main">

    {{-- Topbar --}}
    <div class="topbar">
        <form method="GET" action="{{ route('user.home') }}" class="search-wrap">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24">
                <path d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari buku...">
        </form>

        <div class="user-pill">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            {{ auth()->user()->name }}
        </div>
    </div>

    {{-- Konten halaman --}}
    @yield('content')

</main>

@stack('scripts')
</body>
</html>
