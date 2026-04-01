<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Petugas</title>
</head>
<style>
 /* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', sans-serif;
    background: #f1f5f9;
}

/* LAYOUT */
.dashboard {
    display: flex;
}

/* SIDEBAR */
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
    background: rgba(255,255,255,0.1);
    color: white;
}

.sidebar .active a {
    background: #2563eb;
    color: white;
}

/* MAIN */
.main {
    margin-left: 240px;
    flex: 1;
    padding: 30px;
}

/* HEADER */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.header h1 {
    font-size: 24px;
    font-weight: 600;
}

/* CARDS */
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
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
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

/* TABLE */
.table-box {
    background: white;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
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

/* ACTIONS (SEARCH + BUTTON) */
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

/* BUTTON UTAMA */
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
.action-group button {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
}

/* EDIT */
.edit {
    background: #fef9c3;
    color: #ca8a04;
    border: none;
    padding: 5px 10px;
    border-radius: 999px;
    cursor: pointer;
    transition: 0.3s;
}

.edit:hover {
    background: #fde047;
    color: #92400e;
}

/* HAPUS */
.hapus {
    background: #fee2e2;
    color: #dc2626;
    border: none;
    padding: 5px 10px;
    border-radius: 999px;
    cursor: pointer;
    transition: 0.3s;
}

.hapus:hover {
    background: #fecaca;
    color: #991b1b;
}

/* MODAL */
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
    width: 320px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    animation: fadeIn 0.3s ease;
}

.modal input {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.modal input:focus {
    border-color: #2563eb;
    outline: none;
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
/*logout*/
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
    transform: translateY(-2px);
}
.nav-item.logout svg {
    width: 16px;
    height: 16px;
}
</style>
<body>

<div class="dashboard">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>📚 Petugas</h2>
        <ul>
            <li class="{{ request()->routeIs('petugas.home') ? 'active' : '' }}">
                <a href="{{ route('petugas.home') }}">Dashboard</a>
            </li>
            <li class="{{ request()->routeIs('petugas.anggota') ? 'active' : '' }}">
                <a href="{{ route('petugas.anggota') }}">Kelola Anggota</a>
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