@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary"><i class="fas fa-chalkboard-teacher"></i> Manage Teachers</h2>
    </div>

    <!-- ✅ Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- ✅ Teachers Table -->
    <div class="table-responsive">
        <table id="adminsTable" class="table table-hover table-bordered text-center align-middle w-100">
            <thead class="table-primary text-white">
                <tr>
                    <th>#</th>
                    <th class="text-start">👤 Name</th>
                    <th>📧 Email</th>
                    <th>📅 Registered</th>
                    <th class="text-center">⚙️ Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admins as $index => $admin)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-start">
                            <i class="fas fa-user-tie text-primary"></i> {{ $admin->name }}
                        </td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->created_at->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('admin.admins.edit', $admin->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $admin->id }})">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>

                            <!-- Hidden Delete Form -->
                            <form id="delete-form-{{ $admin->id }}" action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" style="display:none;">
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
    $('#adminsTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "responsive": true,
        "lengthMenu": [10, 25, 50, 100],
        "language": {
            "search": "🔍 Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ teachers",
            "paginate": {
                "next": "⏭",
                "previous": "⏮"
            }
        }
    });
});

// ✅ Delete Confirmation
function confirmDelete(adminId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the teacher's account!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + adminId).submit();
        }
    });
}
</script>
@endsection
