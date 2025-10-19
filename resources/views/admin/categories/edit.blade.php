@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-white text-center">
                    <h4><i class="fas fa-edit"></i> Edit Category</h4>
                </div>
                <div class="card-body">
                    <form id="edit-category-form" action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Category Name Input -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Category Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}" placeholder="Enter new category name..." required>
                            
                            <!-- Error Message -->
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary" id="back-button">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="button" class="btn btn-primary" onclick="confirmUpdate()">
                                <i class="fas fa-save"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmUpdate() {
    let categoryName = document.getElementById('name').value.trim();

    if (categoryName === "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: 'Category name cannot be empty.',
        });
    } else {
        Swal.fire({
            title: 'Update Category?',
            text: `Are you sure you want to update to "${categoryName}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('edit-category-form').submit();
            }
        });
    }
}

// Confirm before navigating back
document.getElementById('back-button').addEventListener('click', function(event) {
    event.preventDefault();
    Swal.fire({
        title: 'Go Back?',
        text: "Your changes won't be saved!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6c757d',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, go back!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('admin.categories.index') }}";
        }
    });
});
</script>

<!-- FontAwesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
