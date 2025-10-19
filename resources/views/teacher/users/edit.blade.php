@extends('layouts.teacher')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4><i class="fas fa-user-edit"></i> Edit User</h4>
                </div>
                <div class="card-body">
                    <form id="update-form" action="{{ route('teacher.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Grade Level -->
                        <div class="mb-3">
                            <label for="grade_level" class="form-label fw-bold">Grade Level</label>
                            <select name="grade_level" id="grade_level" class="form-control" required>
                                <option value="">-- Select Grade Level --</option>
                                <option value="7" {{ old('grade_level', $user->grade_level) == '7' ? 'selected' : '' }}>7</option>
                                <option value="8" {{ old('grade_level', $user->grade_level) == '8' ? 'selected' : '' }}>8</option>
                                <option value="9" {{ old('grade_level', $user->grade_level) == '9' ? 'selected' : '' }}>9</option>
                                <option value="10" {{ old('grade_level', $user->grade_level) == '10' ? 'selected' : '' }}>10</option>
                            </select>
                            @error('grade_level')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="button" class="btn btn-success" id="update-btn">
                                <i class="fas fa-check-circle"></i> Update
                            </button>
                        </div>

                        <input type="hidden" id="original_grade" value="{{ $user->grade_level }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('update-btn').addEventListener('click', function (event) {
        const origGrade = document.getElementById('original_grade').value;
        const selectedGrade = document.getElementById('grade_level').value;

        // If grade changed, show formal warning about resetting progress
        if (selectedGrade !== origGrade) {
            Swal.fire({
                title: "Change Grade Level?",
                text: "Changing grade level will reset this student's progress. Do you want to proceed?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, change and reset progress",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('update-form').submit();
                }
            });
        } else {
            // No grade change — normal confirmation
            Swal.fire({
                title: "Confirm Update",
                text: "Are you sure you want to update this user?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('update-form').submit();
                }
            });
        }
    });
</script>

@endsection
