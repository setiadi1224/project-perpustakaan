<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Petugas</title>
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

    .dashboard {
        display: flex;
    }

    .sidebar {
        width: 240px;
        height: 100vh;
        background: linear-gradient(180deg, #0f172a, #1e293b);
        color: white;
        padding: 20px;
        position: fixed;
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

    .main {
        margin-left: 240px;
        flex: 1;
        padding: 30px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header h1 {
        font-size: 24px;
        font-weight: 600;
    }

    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .card {
        background: white;
        padding: 20px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        transition: 0.3s;
    }

    .card:hover {
        transform: translateY(-6px);
    }

    .card p {
        color: #64748b;
        font-size: 14px;
    }

    .card h2 {
        margin-top: 8px;
        font-size: 22px;
    }

    .table-box {
        background: white;
        padding: 20px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
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

    table tbody tr {
        border-bottom: 1px solid #eee;
        transition: 0.2s;
    }

    table tbody tr:hover {
        background: #f9fafb;
    }

    .actions {
        display: flex;
        gap: 10px;
    }

    .actions input {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        outline: none;
    }

    .actions input:focus {
        border-color: #2563eb;
    }

    .btn {
        background: #2563eb;
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn:hover {
        background: #1d4ed8;
    }

    .action-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .action-group form {
        margin: 0;
    }

    .edit {
        background: #fef9c3;
        color: #ca8a04;
        border: none;
        padding: 5px 10px;
        border-radius: 999px;
        cursor: pointer;
    }

    .edit:hover {
        background: #fde047;
    }

    .hapus {
        background: #fee2e2;
        color: #dc2626;
        border: none;
        padding: 5px 10px;
        border-radius: 999px;
        cursor: pointer;
    }

    .hapus:hover {
        background: #fecaca;
    }

    /* ================= MODAL ================= */
    .modal {
        display: none;
        position: fixed;
        z-index: 999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        justify-content: center;
        align-items: center;
    }

    .modal form {
        background: white;
        padding: 25px;
        border-radius: 14px;
        width: 420px;
        max-width: 90%;
        display: flex;
        flex-direction: column;
        gap: 12px;
        animation: fadeIn 0.3s ease;
    }

    .modal input,
    .modal select,
    .modal textarea {
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
        width: 100%;
        font-size: 14px;
    }

    .modal input:focus,
    .modal select:focus,
    .modal textarea:focus {
        border-color: #2563eb;
        outline: none;
    }

    .modal textarea {
        min-height: 80px;
        resize: vertical;
    }

    .modal h3 {
        text-align: center;
        margin-bottom: 10px;
    }

    .modal .form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }

    @keyframes fadeIn {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
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
    /* PAGINATION */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 25px;
}

.pagination {
    display: flex;
    gap: 8px;
    align-items: center;
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
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #334155;
    transition: all 0.2s ease;
}

/* hover effect */
.pagination a:hover {
    background: #2563eb;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(37, 99, 235, 0.25);
}

/* active */
.pagination .active span {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    color: white;
    border: none;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
}

/* disabled */
.pagination .disabled span {
    background: #f1f5f9;
    color: #94a3b8;
    cursor: not-allowed;
}

/* prev next icon */
.pagination li:first-child a,
.pagination li:last-child a {
    font-weight: bold;
}
:root {
    --bg: #f1f5f9;
    --card: #ffffff;
    --text: #111827;
    --subtext: #6b7280;
    --primary: #2563eb;
}
</style>

<body>
    <div class="dashboard">

        <!-- SIDEBAR -->
        <div class="sidebar">
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
                    <a href="{{ route('petugas.buku') }}">Kelola buku</a>
                </li>
                <li class="{{ request()->routeIs('petugas.peminjaman') ? 'active' : '' }}">
                    <a href="{{ route('petugas.peminjaman') }}">Kelola Peminjaman</a>
                </li>
                <li class="{{ request()->routeIs('petugas.denda') ? 'active' : '' }}">
                    <a href="{{ route('petugas.denda') }}">Kelola denda</a>
                </li>
            </ul>

            <div class="sb-bottom">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item logout">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="main">
            @yield('content')
        </div>

    </div>
    {{-- 🔥 INI WAJIB BANGET --}}
    @yield('script')

</body>

</html>
