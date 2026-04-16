<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital')</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #E8EAF0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 160px;
            height: 100vh;
            background: #0F172A;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            transition: 0.3s;
        }

        .sb-brand {
            padding: 20px;
            color: white;
            font-weight: bold;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sb-nav {
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
        }

        .nav-item {
            padding: 10px;
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 14px;
            transition: 0.2s;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .nav-item.active {
            background: #2563EB;
            color: white;
        }

        /* LOGOUT */
        .sb-bottom {
            padding: 15px;
        }

        .logout-btn {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #ef4444;
            color: white;
        }

        /* ================= MAIN ================= */
        .main {
            margin-left: 160px;
            padding: 24px;
            transition: 0.3s;
        }

        /* ================= TOPBAR ================= */
        .topbar {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .menu-toggle {
            display: none;
            font-size: 22px;
            background: none;
            border: none;
            cursor: pointer;
            margin-right: 10px;
        }

        .user-pill {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            padding: 6px 12px;
            border-radius: 999px;
            font-weight: 600;
            color: black;
        }

        /* AVATAR */
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            overflow: hidden;
            background: #2563EB;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* OVERLAY */
        .overlay {
            display: none;
        }

        /*MOBILE*/
        @media (max-width: 768px) {

            .menu-toggle {
                display: block;
                color: white;
            }

            .sidebar {
                left: -100%;
                width: 200px;
                z-index: 1000;
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            }

            .sidebar.active {
                left: 0;
            }

            .main {
                margin-left: 0;
                padding: 16px;
            }

            .overlay.active {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(255, 255, 255, 0.4);
                z-index: 900;
            }
        }

        /* PAGINATION */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 25px;
        }

        .pagination {
            display: flex;
            gap: 8px;
        }

        .pagination li {
            list-style: none;
        }

        .pagination a,
        .pagination span {
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1px solid #334155;
            background: #1e293b;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 13px;
            transition: 0.2s;
        }

        .pagination a:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-2px);
            border-color: #3b82f6;
        }

        .pagination .active span {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .pagination .disabled span {
            opacity: 0.4;
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- OVERLAY --}}
    <div class="overlay" onclick="closeSidebar()"></div>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sb-brand">Anggota</div>
        <nav class="sb-nav">
            <a href="{{ route('user.home') }}" class="nav-item {{ request()->routeIs('user.home') ? 'active' : '' }}">
                <span>Home</span>
            </a>
            <a href="{{ route('user.library') }}"
                class="nav-item {{ request()->routeIs('user.library') ? 'active' : '' }}">
                <span>Library</span>
            </a>
            <a href="{{ route('user.riwayat') }}"
                class="nav-item {{ request()->routeIs('user.riwayat') ? 'active' : '' }}">
                <span>Riwayat</span>
            </a>
            <a href="{{ route('user.denda') }}" class="nav-item {{ request()->routeIs('user.denda') ? 'active' : '' }}">
                <span>Denda</span>
            </a>
            <a href="{{ route('user.profile') }}"
                class="nav-item {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                <span>Profile</span>
            </a>
        </nav>

        {{-- LOGOUT --}}
        <div class="sb-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">
                    Logout
                </button>
            </form>
        </div>

    </aside>

    {{-- MAIN --}}
    <main class="main">
        <div class="topbar">
            <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
            <div class="user-pill">
                <a href="{{ route('user.profile') }}">
                    <div class="avatar">
                        @if (auth()->user()->foto)
                            <img src="{{ asset('storage/foto/' . auth()->user()->foto) }}">
                        @else
                            <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        @endif
                    </div>
                </a>
                {{ auth()->user()->name }}
            </div>
        </div>
        @yield('content')
    </main>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.overlay').classList.toggle('active');
        }

        function closeSidebar() {
            document.querySelector('.sidebar').classList.remove('active');
            document.querySelector('.overlay').classList.remove('active');
        }
    </script>
    @if (session('warning'))
        <div style="background: orange; padding:10px;">
            ⚠️ {{ session('warning') }}
        </div>
    @endif
</body>

</html>
