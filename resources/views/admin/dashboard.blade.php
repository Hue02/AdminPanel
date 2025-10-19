@extends('layouts.app')
@section('title', 'Admin Dashboard - Quiz Quest')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4 fw-bold">👋 Welcome, Admin!</h2>
    <p class="text-center text-muted">Manage users, trivia, reports, and more from one place.</p>

    <!-- Quick Stats Section -->
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card stat-card text-white bg-primary shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-1"></i>
                    <h5 class="mt-2">Total Users</h5>
                    <h3 class="fw-bold">{{ $userCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card stat-card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-question-circle fs-1"></i>
                    <h5 class="mt-2">Trivia Questions</h5>
                    <h3 class="fw-bold">{{ $triviaCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card stat-card text-white bg-warning shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up fs-1"></i>
                    <h5 class="mt-2">Reports</h5>
                    <h3 class="fw-bold">{{ $reportCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Shortcuts -->
    <div class="row mt-4 g-3">
        <div class="col-12 col-sm-6 col-md-4">
            <a href="{{ route('admin.users.index') }}" class="card action-card shadow-sm text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-person-lines-fill fs-1 text-primary"></i>
                    <h5 class="mt-2">Manage Users</h5>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <a href="{{ route('admin.trivia.index') }}" class="card action-card shadow-sm text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-list-task fs-1 text-success"></i>
                    <h5 class="mt-2">Manage Trivia</h5>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <a href="{{ route('admin.reports.index') }}" class="card action-card shadow-sm text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-bar-graph fs-1 text-warning"></i>
                    <h5 class="mt-2">View Reports</h5>
                </div>
            </a>
        </div>
    </div>

    <!-- Profile & Logout -->
    <div class="row mt-4 g-3">
        <div class="col-12 col-sm-6">
            <a href="{{ route('admin.profile') }}" class="card action-card shadow-sm text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-person-circle fs-1 text-info"></i>
                    <h5 class="mt-2">My Profile</h5>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6">
            <form id="logoutForm" action="{{ route('admin.logout') }}" method="POST" class="card shadow-sm logout-card">
                @csrf
                <button type="button" id="logoutBtnHm" class="card-body text-center btn btn-link text-danger">
                    <i class="bi bi-box-arrow-right fs-1"></i>
                    <h5 class="mt-2">Logout</h5>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById("logoutBtnHm").addEventListener("click", function (event) {
        event.preventDefault();

        Swal.fire({
            title: "Are you sure?",
            text: "You will be logged out!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, logout!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("logoutForm").submit();
            }
        });
    });
</script>

<!-- Custom Styles -->
<style>
    .stat-card, .action-card, .logout-card {
        transition: transform 0.2s ease-in-out;
    }

    .stat-card:hover, .action-card:hover {
        transform: scale(1.05);
    }

    .action-card {
        border: 1px solid transparent;
        transition: all 0.3s ease-in-out;
    }

    .action-card:hover {
        background-color: rgba(0, 0, 0, 0.05);
        border-color: rgba(0, 0, 0, 0.1);
    }

    .logout-card:hover {
        background-color: rgba(255, 0, 0, 0.1);
    }
</style>

@endsection
