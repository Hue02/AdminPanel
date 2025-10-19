@extends('layouts.teacher')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary"><i class="fas fa-users"></i> Manage Users</h2>
        
        <!-- Export Button Dropdown -->
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-download"></i> Export Data
            </button>
            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                <li><a class="dropdown-item export-btn" data-type="copy"><i class="fas fa-copy"></i> Copy</a></li>
                <li><a class="dropdown-item export-btn" data-type="excel"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item export-btn" data-type="csv"><i class="fas fa-file-csv"></i> CSV</a></li>
                <li><a class="dropdown-item export-btn" data-type="pdf"><i class="fas fa-file-pdf"></i> PDF</a></li>
                <li><a class="dropdown-item export-btn" data-type="print"><i class="fas fa-print"></i> Print</a></li>
            </ul>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- User Table -->
    <div class="table-responsive">
        <table id="usersTable" class="table table-hover table-bordered text-center align-middle w-100">
            <thead class="table-info text-white">
                <tr>
                    <th>#</th>
                    <th class="text-start">👤 Name</th>
                    <th>📧 Email</th>
                    <th>🎓 Grade</th>
                    <th>📅 Registered</th>
                    <th class="text-center">⚙️ Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-start">
                            <i class="fas fa-user-circle text-primary"></i> {{ $user->name }}
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->grade_level ? $user->grade_level : '—' }}</td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning px-3 me-2" onclick="editUser({{ $user->id }})">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger px-3" onclick="confirmDelete({{ $user->id }})">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>

                            <form id="delete-form-{{ $user->id }}" action="{{ route('teacher.users.destroy', $user->id) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
            { extend: 'pdfHtml5', className: 'd-none', attr: { 'data-export': 'pdf' }, orientation: 'portrait', pageSize: 'A4' },
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
    window.location.href = "{{ route('teacher.users.edit', ':id') }}".replace(':id', userId);
}
</script>
@endsection
