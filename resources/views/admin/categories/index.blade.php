@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h2 class="fw-bold text-primary mb-2"><i class="fas fa-folder"></i> Categories</h2>
        
        <div class="d-flex gap-2">
            <!-- ✅ Floating Add Button for Desktop -->
             
            <a href="{{ route('admin.categories.create') }}" class="btn btn-success rounded-pill shadow-sm">
            <i class="fas fa-plus-circle"></i> Add Category
            </a>

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
    </div>

    <!-- ✅ Floating Button for Mobile -->
    <a href="{{ route('admin.categories.create') }}" 
    class="btn btn-success rounded-circle shadow-lg d-md-none position-fixed"
    style="
        bottom: 80px;
        right: 20px; 
        width: 55px; 
        height: 55px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        z-index: 1050;
        background-color: #28a745;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    ">
        <i class="fas fa-plus fa-lg text-white"></i>
    </a>

    <!-- ✅ Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- ✅ Category List -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="categoriesTable" class="table table-hover align-middle text-center w-100">
                    <thead class="table-primary text-white">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td class="fw-bold text-primary">{{ $loop->iteration }}</td>
                                <td class="text-start"><i class="fas fa-folder-open text-warning"></i> {{ $category->name }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning btn-sm rounded mx-2">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Delete Button -->
                                        <button class="btn btn-danger btn-sm rounded mx-2" onclick="confirmDelete({{ $category->id }})">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>

                                    <!-- Hidden Delete Form -->
                                    <form id="delete-form-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:none;">
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
    </div>
</div>

<script>
$(document).ready(function() {
    var table = $('#categoriesTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "responsive": true,
        "lengthMenu": [10, 25, 50, 100],
        "language": {
            "search": "🔍 Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ categories",
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

    // Trigger button clicks from dropdown
    $('#copyBtn').click(() => table.button('.buttons-copy').trigger());
    $('#excelBtn').click(() => table.button('.buttons-excel').trigger());
    $('#csvBtn').click(() => table.button('.buttons-csv').trigger());
    $('#pdfBtn').click(() => table.button('.buttons-pdf').trigger());
    $('#printBtn').click(() => table.button('.buttons-print').trigger());
});

// ✅ SweetAlert2 Delete Confirmation
function confirmDelete(categoryId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the category!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + categoryId).submit();
        }
    });
}
</script>

<!-- ✅ FontAwesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
