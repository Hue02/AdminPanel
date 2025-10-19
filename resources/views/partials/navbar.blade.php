<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('teacher.dashboard') }}">
            <i class="bi bi-mortarboard"></i> Quiz Quest
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('teacher/dashboard') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('teacher/users*') ? 'active' : '' }}" href="{{ route('teacher.users.index') }}">
                        <i class="bi bi-people"></i> Students
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('teacher/trivia*') ? 'active' : '' }}" href="{{ route('teacher.trivia.index') }}">
                        <i class="bi bi-question-circle"></i> Trivia
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('teacher/profile') ? 'active' : '' }}" href="{{ route('teacher.profile') }}">
                        <i class="bi bi-person-circle"></i> Profile
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-info-circle"></i> About
                    </a>
                </li>
            </ul>

            <!-- User Profile Dropdown -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('teacher.profile') }}">
                            <i class="bi bi-gear"></i> Profile Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button type="button" class="dropdown-item text-danger" id="logout-btn">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Logout Form (Hidden) -->
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById("logout-btn").addEventListener("click", function(event) {
        event.preventDefault();
        Swal.fire({
            title: "Are you sure?",
            text: "You will be logged out of your account.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, logout",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("logout-form").submit();
            }
        });
    });
</script>
