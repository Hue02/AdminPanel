@extends('layouts.teacher')

@section('content')
<div class="container mt-5">
    <div class="card profile-card p-4 shadow-lg">
        <div class="text-center">
            <h2 class="fw-bold mb-1">👨‍🏫 Teacher Profile</h2>
            <p class="text-muted">Manage your account settings and personal information.</p>
        </div>

        <div class="text-center profile-iamg mb-4 position-relative">
            <img src="{{ Auth::user()->profile_picture }}" 
                alt="Profile Picture" 
                class="rounded-circle shadow profile-picture">
            
            <button type="button" class="btn btn-sm btn-dark btn-edit position-absolute bottom-0 end-0" 
                    data-bs-toggle="modal" data-bs-target="#changePictureModal">
                <i class="bi bi-camera"></i>
            </button>
        </div>

        <div class="profile-info p-3 bg-light rounded text-center shadow-sm">
            <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
            <p class="text-muted mb-2">{{ Auth::user()->email }}</p>
            <p class="text-secondary mb-0"><i class="bi bi-calendar"></i> Joined: {{ Auth::user()->created_at->format('d M Y') }}</p>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <button class="btn btn-outline-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#editAccountModal">
                <i class="bi bi-pencil-square"></i> Edit Info
            </button>
            <button class="btn btn-outline-warning shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                <i class="bi bi-key"></i> Change Password
            </button>
        </div>
    </div>
</div>

<!-- Modals -->
<div class="modal fade" id="changePictureModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.profile.picture.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">🖼️ Change Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input class="form-control" type="file" name="profile_picture" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary shadow-sm">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">✏️ Edit Account Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control mb-3" name="name" value="{{ Auth::user()->name }}" required>
                    <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary shadow-sm">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.password.update') }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">🔑 Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="password" class="form-control mb-3" name="current_password" placeholder="Current Password" required>
                    <input type="password" class="form-control mb-3" name="new_password" placeholder="New Password" required>
                    <input type="password" class="form-control" name="new_password_confirmation" placeholder="Confirm New Password" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary shadow-sm">Update Password</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .profile-card {
        max-width: 500px;
        margin: auto;
        border-radius: 12px;
        background: white;
        transition: 0.3s;
    }

    .profile-picture {
        width: 150px;
        height: 150px;
        object-fit: cover;
        object-position: center;
        border-radius: 50%;
        border: 3px solid #ddd;
        transition: transform 0.3s ease-in-out;
    }

    .profile-picture:hover {
        transform: scale(1.05);
    }

    .btn-edit {
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease-in-out;
        border: none;
    }

    .btn-edit:hover {
        background-color: #333;
        transform: scale(1.1);
    }

    .profile-info {
        font-size: 1rem;
    }

    .btn {
        font-size: 0.9rem;
        border-radius: 8px;
        transition: all 0.3s ease-in-out;
    }

    .btn:hover {
        transform: scale(1.05);
    }
</style>
@endsection
