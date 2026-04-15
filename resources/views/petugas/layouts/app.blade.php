<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Petugas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f1f5f9;
    }

    /* ================= LAYOUT ================= */
    .dashboard {
        display: flex;
    }

    /* ================= SIDEBAR ================= */
    .sidebar {
        width: 240px;
        height: 100vh;
        background: linear-gradient(180deg, #0f172a, #1e293b);
        color: white;
        padding: 20px;
        position: fixed;
        left: 0;
        top: 0;
        transition: 0.3s;
        z-index: 1000;
        display: flex;
        flex-direction: column;
    }

    .sidebar.hide {
        left: -260px;
    }

    .sidebar h2 {
        margin-bottom: 30px;
        font-size: 20px;
    }

    .sidebar ul {
        list-style: none;
    }

    .sidebar ul li {
        margin-bottom: 10px;
    }

    .sidebar ul li a {
        display: block;
        padding: 10px 12px;
        color: #cbd5f5;
        text-decoration: none;
        border-radius: 8px;
        transition: 0.3s;
    }

    .sidebar ul li a:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .sidebar .active a {
        background: #2563eb;
        color: white;
    }

    /* ================= MAIN ================= */
    .main {
        margin-left: 240px;
        flex: 1;
        padding: 30px;
        transition: 0.3s;
    }

    /* ================= HAMBURGER ================= */
    .toggle-btn {
        display: none;
        position: fixed;
        top: 15px;
        left: 15px;
        color: rgb(0, 0, 0);
        border: none;
        padding: 10px 12px;
        border-radius: 8px;
        cursor: pointer;
        z-index: 1100;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {

        .toggle-btn {
            display: block;
        }

        .sidebar {
            position: fixed;
            left: -240px;
            transition: 0.3s;
            z-index: 1000;
        }

        .sidebar.active {
            left: 0;
        }

        .main {
            margin-left: 0;
            width: 100%;
        }

        .sidebar.show {
            left: 0;
        }

        .main {
            margin-left: 0;
            padding: 20px;
        }
    }

    /* ================= CARD ================= */
    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    /* ================= TABLE ================= */
    .table-box {
        background: white;
        padding: 20px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }

    table th {
        text-align: left;
        padding: 12px;
        font-size: 13px;
        color: #64748b;
        background: #f8fafc;
    }

    table td {
        padding: 12px;
        font-size: 14px;
    }

    /* ================= BUTTON ================= */
    .btn {
        background: #2563eb;
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        cursor: pointer;
    }

    /* LOGOUT */
    .sb-bottom {
        padding: 15px;
        margin-top: auto;
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

    /* ================= PAGINATION ================= */
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
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #374151;
        text-decoration: none;
        font-size: 13px;
        transition: 0.2s;
    }

    .pagination a:hover {
        background: #3b82f6;
        color: white;
        transform: translateY(-2px);
    }

    .pagination .active span {
        background: #3b82f6;
        color: white;
    }

    .pagination .disabled span {
        opacity: 0.5;
    }
</style>

<body>

    <!-- TOGGLE BUTTON -->
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

    <div class="dashboard">

        <!-- SIDEBAR -->
        <div class="sidebar" id="sidebar">
            <h2>Petugas</h2>
            <ul>
                <li class="{{ request()->routeIs('petugas.home') ? 'active' : '' }}">
                    <a href="{{ route('petugas.home') }}">Dashboard</a>
                </li>
                <li class="{{ request()->routeIs('petugas.anggota') ? 'active' : '' }}">
                    <a href="{{ route('petugas.anggota') }}">Kelola Anggota</a>
                </li>
                <li class="{{ request()->routeIs('petugas.kategori') ? 'active' : '' }}">
                    <a href="{{ route('petugas.kategori') }}">Kelola Kategori</a>
                </li>
                <li class="{{ request()->routeIs('petugas.buku') ? 'active' : '' }}">
                    <a href="{{ route('petugas.buku') }}">Kelola Buku</a>
                </li>
                <li class="{{ request()->routeIs('petugas.peminjaman') ? 'active' : '' }}">
                    <a href="{{ route('petugas.peminjaman') }}">Kelola Peminjaman</a>
                </li>
                <li class="{{ request()->routeIs('petugas.pengembalian') ? 'active' : '' }}">
                    <a href="{{ route('petugas.pengembalian') }}">Pengembalian</a>
                </li>
                <li class="{{ request()->routeIs('petugas.denda') ? 'active' : '' }}">
                    <a href="{{ route('petugas.denda') }}">Kelola Denda</a>
                </li>
            </ul>

            <div class="sb-bottom">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout-btn">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- MAIN -->
        <div class="main">
            @yield('content')
        </div>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }
    </script>

    @yield('script')

</body>

</html>
