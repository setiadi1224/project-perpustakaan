@extends('kepala.layouts.app')

@section('title', 'Security Monitoring')

@section('content')

<style>
/* 🔥 khusus security biar tetap dark tapi nyatu */
.security-wrap {
    background: #0b1220;
    padding: 20px;
    border-radius: 16px;
    color: #e5e7eb;
}

/* stats */
.stats {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.stats .card {
    flex: 1;
    background: #111827;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
}

/* search */
.search-box input {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: none;
    background: #111827;
    color: white;
    margin-bottom: 15px;
}

/* table */
table {
    width: 100%;
    border-collapse: collapse;
    background: #0f172a;
    border-radius: 10px;
    overflow: hidden;
}

th {
    background: #111827;
    padding: 12px;
    font-size: 13px;
}

td {
    padding: 12px;
    border-bottom: 1px solid #1f2937;
}

tr:hover {
    background: #1f2937;
}

/* level */
.high { background: #7f1d1d !important; }
.medium { background: #7c2d12 !important; }

.badge {
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
}

.badge-high { background: red; }
.badge-medium { background: orange; color: black; }
.badge-low { background: green; }

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

<div class="security-wrap">

    <h2>🚨 Security Monitoring System (AI IDS)</h2>

    {{-- STATS --}}
    <div class="stats">
        <div class="card">
            <h3>{{ $logs->count() }}</h3>
            <p>Total Logs</p>
        </div>

        <div class="card">
            <h3>{{ $logs->where('level','HIGH')->count() }}</h3>
            <p>HIGH Risk</p>
        </div>

        <div class="card">
            <h3>{{ $logs->where('level','MEDIUM')->count() }}</h3>
            <p>MEDIUM Risk</p>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="search-box">
        <input type="text" id="search" placeholder="Cari email / IP..." onkeyup="searchTable()">
    </div>

    {{-- TABLE --}}
    <table id="securityTable">
        <thead>
            <tr>
                <th>Email</th>
                <th>IP</th>
                <th>Status</th>
                <th>Level</th>
                <th>Risk Score</th>
                <th>Waktu</th>
            </tr>
        </thead>

        <tbody>
            @foreach($logs as $log)
            <tr class="@if($log->level == 'HIGH') high @elseif($log->level == 'MEDIUM') medium @endif">
                <td>{{ $log->email }}</td>
                <td>{{ $log->ip }}</td>
                <td>{{ $log->status }}</td>
                <td>
                    <span class="badge
                        @if($log->level == 'HIGH') badge-high
                        @elseif($log->level == 'MEDIUM') badge-medium
                        @else badge-low
                        @endif
                    ">
                        {{ $log->level }}
                    </span>
                </td>
                <td>{{ number_format($log->risk_score, 2) }}</td>
                <td>{{ $log->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
<div class="pagination-wrapper">
    
    {{ $logs->links() }}
</div>
</div>

<script>
function searchTable() {
    let input = document.getElementById("search");
    let filter = input.value.toLowerCase();
    let rows = document.querySelectorAll("#securityTable tbody tr");

    rows.forEach((row, index) => {
        if (index === 0) return;
        row.style.display = row.textContent.toLowerCase().includes(filter) ? "" : "none";
    });
}
</script>

<script>
setInterval(() => location.reload(), 10000);
</script>

@endsection