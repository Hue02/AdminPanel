    <div class="sidebar d-flex flex-column" id="sidebar">
        <!-- ✅ Admin Profile Card -->
        <div class="card bg-dark text-white shadow-sm p-3 mb-3">
            <div class="d-flex align-items-center">
                @if(Auth::guard('admin')->check()) 
                    <img src="{{ Auth::guard('admin')->user()->profile_picture ?? asset('default-profile.png') }}" 
                        alt="Admin Profile" 
                        class="rounded-circle me-3 shadow-sm" 
                        style="width: 60px; height: 60px; object-fit: cover;">
                    <div>
                        <h6 class="fw-bold mb-0">{{ Auth::guard('admin')->user()->name }}</h6>
                        <small class="text-light">Administrator</small>
                    </div>
                @endif
            </div>
        </div>


        <!-- ✅ Game Logo and Title -->
        <div class="sidebar-brand text-center border-bottom">
            <img src="{{ asset('assets/logo.webp') }}" alt="Quiz Quest Logo" 
                class="img-fluid rounded" style="width: 70px; height: auto;">
            <h5 class="fw-bold text-light mt-2">Quiz Quest: 2D Trivia</h5>
        </div>

         <!-- ✅ Scrollable Navigation -->
    <div class="flex-grow-1 sidebar-content">
        <ul class="nav flex-column">
            <li>
                <a href="{{ route('admin.dashboard') }}" 
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-primary fs-5' : '' }}">
                   <i class="bi bi-house-door"></i> Home
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" 
                    class="nav-link {{ request()->routeIs('admin.users.index') ? 'active bg-primary fs-5' : '' }}">
                    <i class="bi bi-person-lines-fill"></i> Students
                    </a>
                </li>
            <li>
                <a href="{{ route('admin.admins.index') }}" 
                class="nav-link {{ request()->routeIs('admin.admins.index') ? 'active bg-primary fs-5' : '' }}">
                <i class="bi bi-person-badge-fill"></i> Teachers
                </a>
            </li>
            <li>
                <a href="{{ route('admin.trivia.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.trivia.index') ? 'active bg-primary fs-5' : '' }}">
                   <i class="bi bi-question-circle"></i> Trivia
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" 
                class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active bg-primary fs-5' : '' }}">
                <i class="bi bi-folder"></i> Categories
                </a>
            </li>

            <li>
                <a href="{{ route('admin.reports.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.reports.index') ? 'active bg-primary fs-5' : '' }}">
                   <i class="bi bi-bar-chart"></i> Student Progress
                </a>
            </li>
            <li>
                <a href="{{ route('admin.profile') }}" 
                   class="nav-link {{ request()->routeIs('admin.profile') ? 'active bg-primary fs-5' : '' }}">
                   <i class="bi bi-person-circle"></i> Profile
                </a>
            </li>
        </ul>
    </div>

        <!-- Logout Button (SweetAlert2 Confirmation) -->
        <form method="POST" action="{{ route('admin.logout') }}" class="logout-form mt-auto" id="logoutForm">
            @csrf
            <button type="button" class="btn btn-danger w-100" id="logoutBtn">🚪 Logout</button>
        </form>
    </div>



    <!-- Mobile Toggle Button -->
    <button class="btn btn-dark d-md-none toggle-btn-mobile" id="toggleSidebarMobile">
        <i class="bi bi-list"></i>
    </button>

    <!-- ✅ Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('logoutBtn').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent form submission

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
                    document.getElementById('logoutForm').submit(); // Submit form on confirmation
                }
            });
        });
    </script>
