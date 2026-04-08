<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
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
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: 160px;
            min-height: 100vh;
            background: #0F172A;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
        }

        .sb-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        .brand-icon {
            width: 30px;
            height: 30px;
            background: #2563EB;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .sb-brand span {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
        }

        .sb-nav {
            padding: 14px 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 10px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.55);
            text-decoration: none;
            transition: 0.2s;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .nav-item.active {
            background: #2563EB;
            color: #fff;
        }

        .nav-item.logout {
            color: #ff6b6b;
        }

        .sb-bottom {
        position: absolute;
        bottom: 20px;
        left: 20px;
        right: 20px;
    }

    .nav-item.logout {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(239, 68, 68, 0.1);
        color: #f87171;
        border: none;
        padding: 10px 12px;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.3s;
        font-size: 14px;
    }

    .nav-item.logout:hover {
        background: #ef4444;
        color: white;
    }

        /* MAIN */
        .main {
            margin-left: 160px;
            flex: 1;
            padding: 24px;
        }

        .topbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #374151;
            background: #fff;
            padding: 6px 12px;
            border-radius: 999px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        /* AVATAR FIX */
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #2563EB;
            color: #fff;
            font-weight: 700;
            flex-shrink: 0;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .avatar span {
            font-size: 13px;
        }

        @media (max-width: 580px) {
            .sidebar {
                width: 60px;
            }

            .sb-brand span,
            .nav-item span {
                display: none;
            }

            .main {
                margin-left: 60px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sb-brand">
            <span>Anggota</span>
        </div>

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

        <div class="sb-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item logout">
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="main">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="user-pill">

                <div class="avatar">
                    @if (auth()->user()->foto)
                        <img src="{{ asset('storage/foto/' . auth()->user()->foto) }}" alt="avatar">
                    @else
                        <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    @endif
                </div>

                {{ auth()->user()->name }}
            </div>
        </div>

        {{-- CONTENT --}}
        @yield('content')

    </main>

    @stack('scripts')
</body>

</html>