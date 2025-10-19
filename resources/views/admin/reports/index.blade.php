@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-primary mb-4"><i class="fas fa-chart-line"></i> User Progress Reports</h2>

        <div class="d-flex justify-content-end mb-2">
            <!-- 🔽 Dropdown for Export Buttons -->
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-download"></i> Download
                </button>
                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                    <li><button class="dropdown-item" id="copyBtn"><i class="fas fa-copy"></i> Copy</button></li>
                    <li><button class="dropdown-item" id="excelBtn"><i class="fas fa-file-excel"></i> Excel</button></li>
                    <li><button class="dropdown-item" id="csvBtn"><i class="fas fa-file-csv"></i> CSV</button></li>
                    <li><button class="dropdown-item" id="pdfBtn"><i class="fas fa-file-pdf"></i> PDF</button></li>
                    <li><button class="dropdown-item" id="printBtn"><i class="fas fa-print"></i> Print</button></li>
                </ul>
            </div>
        </div>

    <!-- ✅ Toggle Buttons for Table & Chart -->
    <div class="d-flex flex-wrap justify-content-center justify-content-md-between align-items-center mb-3">
        <div class="btn-group">
            <button class="btn btn-outline-primary active" id="tableBtn" onclick="toggleView('table')">
                <i class="fas fa-table"></i> Table View
            </button>
            <button class="btn btn-outline-success" id="chartBtn" onclick="toggleView('chart')">
                <i class="fas fa-chart-bar"></i> Chart View
            </button>
        </div>
    </div>

    <!-- ✅ Table View with DataTables -->
    <div id="tableView" class="card shadow p-3">
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center align-middle" id="userTable" style="width: 100%;">
                <thead class="table-info text-white">
                    <tr>
                        <th>#</th>
                        <th class="text-start">👤 User</th>
                        <th>📊 Level</th>
                        <th>🪙 Coins</th>
                        <th>✅ Correct</th>
                        <th>❌ Incorrect</th>
                        <th>⏱ Time</th>
                        <th>📅 Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userProgress as $index => $progress)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-start fw-bold">
                                <i class="fas fa-user-circle text-primary"></i> {{ $progress->user->name }}
                            </td>
                            <td class="fw-bold text-info">{{ $progress->level }}</td>
                            <td class="fw-bold text-warning">{{ $progress->coins }}</td>
                            <td class="fw-bold text-success">{{ $progress->correct_answers }}</td>
                            <td class="fw-bold text-danger">{{ $progress->incorrect_answers }}</td>
                            <td>{{ $progress->completion_time ?? 'N/A' }} sec</td>
                            <td>{{ $progress->updated_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ✅ Chart View -->
    <div id="chartView" class="card shadow p-4 text-center" style="display: none;">
        <canvas id="progressChart" style="max-height: 400px;"></canvas>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#userTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "responsive": true,
            "lengthMenu": [10, 25, 50, 100],
            "language": {
                "search": "🔍 Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ users",
                "paginate": {
                    "next": "⏭",
                    "previous": "⏮"
                }
            },
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copyHtml5', text: 'Copy', className: 'd-none', attr: { id: 'copyBtn' } },
                { extend: 'excelHtml5', text: 'Excel', className: 'd-none', attr: { id: 'excelBtn' } },
                { extend: 'csvHtml5', text: 'CSV', className: 'd-none', attr: { id: 'csvBtn' } },
                { extend: 'pdfHtml5', text: 'PDF', className: 'd-none', attr: { id: 'pdfBtn' }, orientation: 'portrait', pageSize: 'A4' },
                { extend: 'print', text: 'Print', className: 'd-none', attr: { id: 'printBtn' } }
            ]
        });

        // Trigger export buttons from dropdown
        $('#copyBtn').click(() => table.button('.buttons-copy').trigger());
        $('#excelBtn').click(() => table.button('.buttons-excel').trigger());
        $('#csvBtn').click(() => table.button('.buttons-csv').trigger());
        $('#pdfBtn').click(() => table.button('.buttons-pdf').trigger());
        $('#printBtn').click(() => table.button('.buttons-print').trigger());
    });

    // ✅ Chart Data
    let chartData = @json($chartData);
    let userNames = chartData.map(data => data.user.name);
    let userLevels = chartData.map(data => data.level);
    let userCoins = chartData.map(data => data.coins);

    // ✅ Initialize Chart.js
    let ctx = document.getElementById('progressChart').getContext('2d');
    let progressChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: userNames,
            datasets: [
                {
                    label: 'Level',
                    data: userLevels,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Coins',
                    data: userCoins,
                    backgroundColor: 'rgba(255, 206, 86, 0.6)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // ✅ Toggle between table & chart view
    function toggleView(view) {
        if (view === 'table') {
            document.getElementById('tableView').style.display = 'block';
            document.getElementById('chartView').style.display = 'none';
            document.getElementById('tableBtn').classList.add('active');
            document.getElementById('chartBtn').classList.remove('active');
        } else {
            document.getElementById('tableView').style.display = 'none';
            document.getElementById('chartView').style.display = 'block';
            document.getElementById('chartBtn').classList.add('active');
            document.getElementById('tableBtn').classList.remove('active');
        }
    }
</script>
@endsection
