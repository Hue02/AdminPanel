@extends('layouts.auth')

@section('title', 'Admin Login - Quiz Quest')

@section('content')
<div class="login-container">
    <!-- ✅ Left Section (Game Branding) -->
    <div class="login-left">
        <img src="{{ asset('assets/logo.webp') }}" alt="Quiz Quest Logo" class="login-logo"> 
        <h2>Welcome to Quiz Quest</h2>
        <p>Manage trivia, track players, and keep the game exciting! sasasasasas</p>
    </div>

    <!-- ✅ Right Section (Login Form) -->
    <div class="login-right">
        <h2>🔑 Admin Login</h2>
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="input-group">
                <input type="email" name="email" placeholder="📧 Email" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="🔒 Password" required>
            </div>
            <button type="submit">🚀 Login</button>
        </form>
        
        <div class="login-info">
            <p>📜 Secure access to manage Quiz Quest!</p>
        </div>
    </div>
</div>

<!-- ✅ SweetAlert2 Flash Messages -->
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: "{{ session('success') }}",
        confirmButtonColor: '#007bff',
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "{{ session('error') }}",
        confirmButtonColor: '#dc3545',
    });
</script>
@endif
@endsection