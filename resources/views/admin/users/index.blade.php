@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <!-- Header with Export + Add User -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h2 class="fw-bold text-primary"><i class="fas fa-users"></i> Manage Users</h2>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-download"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                    <li><a class="dropdown-item export-btn" data-type="copy"><i class="fas fa-copy"></i> Copy</a></li>
                    <li><a class="dropdown-item export-btn" data-type="excel"><i class="fas fa-file-excel"></i> Excel</a></li>
                    <li><a class="dropdown-item export-btn" data-type="csv"><i class="fas fa-file-csv"></i> CSV</a></li>
                    <li><a class="dropdown-item export-btn" data-type="pdf"><i class="fas fa-file-pdf"></i> PDF</a></li>
                    <li><a class="dropdown-item export-btn" data-type="print"><i class="fas fa-print"></i> Print</a></li>
                </ul>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Add User
            </a>
        </div>
    </div>

    <!-- Alert Section -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Users Table -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table id="usersTable" class="table table-hover align-middle text-center mb-0">
                <thead class="table-primary text-white">
                    <tr>
                        <th>#</th>
                        <th class="text-start">👤 Name</th>
                        <th>📧 Email</th>
                        <th>🎓 Grade</th>
                        <th>📅 Registered</th>
                        <th>⚙️ Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    @php $p = $user->progress; @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-start"><i class="fas fa-user-circle text-primary"></i> {{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->grade_level ? 'Grade '.$user->grade_level : '—' }}</td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>

                            <div class="d-flex justify-content-center gap-2 mb-2">
                                <button class="btn btn-warning btn-sm" onclick="editUser({{ $user->id }})">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }})">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>

                            <!-- ✅ Hidden Delete Form -->
                            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>

                            <button class="btn btn-outline-info btn-sm w-100 toggle-grade" data-bs-toggle="collapse" data-bs-target="#grades-{{ $user->id }}">
                                📊 View Grades <i class="fas fa-chevron-down ms-1"></i>
                            </button>

                            <div id="grades-{{ $user->id }}" class="collapse mt-2">
                                @if ($p)
                                    <table class="table table-sm table-bordered bg-light">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Correct</th>
                                                <th>Incorrect</th>
                                                <th>Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (['Math', 'Science', 'General'] as $subject)
                                                @php
                                                    $correct = $p->{strtolower($subject).'_correct'};
                                                    $incorrect = $p->{strtolower($subject).'_incorrect'};
                                                    $grade = ($correct + $incorrect) > 0 ? round(($correct / ($correct + $incorrect)) * 100) . '%' : 'N/A';
                                                @endphp
                                                <tr>
                                                    <td>{{ $subject }}</td>
                                                    <td>{{ $correct }}</td>
                                                    <td>{{ $incorrect }}</td>
                                                    <td>{{ $grade }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <em>No progress data yet.</em>
                                @endif
                            </div>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    var table = $('#usersTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "responsive": true,
        "lengthMenu": [10, 25, 50, 100],
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', className: 'd-none', attr: { 'data-export': 'copy' } },
            { extend: 'excelHtml5', className: 'd-none', attr: { 'data-export': 'excel' } },
            { extend: 'csvHtml5', className: 'd-none', attr: { 'data-export': 'csv' } },
            { extend: 'pdfHtml5', className: 'd-none', attr: { 'data-export': 'pdf' } },
            { extend: 'print', className: 'd-none', attr: { 'data-export': 'print' } }
        ]
    });

    $('.export-btn').on('click', function() {
        var type = $(this).data('type');
        table.buttons('[data-export="' + type + '"]').trigger();
    });
});

function confirmDelete(userId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + userId).submit();
        }
    });
}

function editUser(userId) {
    window.location.href = "{{ route('admin.users.edit', ':id') }}".replace(':id', userId);
}
</script>
@endsection
