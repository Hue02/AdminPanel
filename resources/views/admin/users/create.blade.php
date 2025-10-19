@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary fw-bold mb-4"><i class="fas fa-user-plus"></i> Add New User</h2>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-bold">Full Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Email Address</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Grade Level</label>
            <select name="grade_level" class="form-control" required>
                <option value="">-- Select Grade Level --</option>
                <option value="7" {{ old('grade_level') == '7' ? 'selected' : '' }}>Grade 7</option>
                <option value="8" {{ old('grade_level') == '8' ? 'selected' : '' }}>Grade 8</option>
                <option value="9" {{ old('grade_level') == '9' ? 'selected' : '' }}>Grade 9</option>
                <option value="10" {{ old('grade_level') == '10' ? 'selected' : '' }}>Grade 10</option>
            </select>
            @error('grade_level')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Create User</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection
