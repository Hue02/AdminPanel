@extends('layouts.teacher')
@section('title', 'Teacher Dashboard - Quiz Quest')

@section('content')
<div class="container mt-5">

    <!-- 🌟 Welcome Card -->
    <div class="card shadow-lg border-0 rounded-4 p-4 text-center bg-gradient" 
         style="background: linear-gradient(135deg, #4facfe, #00f2fe); color: white;">
        <h1 class="fw-bold mb-2 text-dark">
            <i class="fas fa-chalkboard-teacher"></i> Welcome, Teacher!
        </h1>
        <p class="lead mb-0">Manage your students, trivia, and performance reports with ease.</p>
    </div>

    <!-- 📊 Dashboard Quick Stats -->
    <div class="row mt-5 g-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 text-center p-4 h-100 hover-card">
                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                <h4 class="fw-bold">Students</h4>
                <p class="text-muted">View and manage your students</p>
                <a href="{{ route('teacher.users.index') }}" class="btn btn-outline-primary btn-sm">Manage</a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 text-center p-4 h-100 hover-card">
                <i class="fas fa-question-circle fa-3x text-success mb-3"></i>
                <h4 class="fw-bold">Trivia</h4>
                <p class="text-muted">Create and edit trivia questions</p>
                <a href="{{ route('teacher.trivia.index') }}" class="btn btn-outline-success btn-sm">Go</a>
            </div>
        </div>
    </div>

    <!-- 📈 Reports Section -->
    <div class="card shadow-sm border-0 mt-5 p-4">
        <h4 class="fw-bold mb-3 text-warning">
            <i class="fas fa-chart-line"></i> Performance Reports
        </h4>
        
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Student</th>
                        <th>Level</th>
                        <th>Correct Answers</th>
                        <th>Incorrect Answers</th>
                        <th>Coins Earned</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports ?? [] as $report)
                        <tr>
                            <td>{{ $report->user->name ?? 'Unknown' }}</td>
                            <td>{{ $report->level }}</td>
                            <td class="text-success fw-bold">{{ $report->correct_answers }}</td>
                            <td class="text-danger fw-bold">{{ $report->incorrect_answers }}</td>
                            <td class="text-primary fw-bold">{{ $report->coins }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($reports->isEmpty())
            <p class="text-muted text-center">No reports available yet.</p>
        @endif
    </div>

    <!-- 🎯 Tips Section -->
    <div class="card shadow-sm border-0 mt-5 p-4 bg-light">
        <h5 class="fw-bold mb-3"><i class="fas fa-lightbulb text-warning"></i> Quick Tips</h5>
        <ul class="list-unstyled mb-0">
            <li><i class="fas fa-check-circle text-success"></i> Keep trivia engaging and balanced.</li>
            <li><i class="fas fa-check-circle text-success"></i> Monitor student progress regularly.</li>
            <li><i class="fas fa-check-circle text-success"></i> Use reports to identify learning gaps.</li>
        </ul>
    </div>
</div>

<!-- 🔹 Small CSS touch for hover effect -->
<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 8px 20px rgba(0,0,0,0.15);
    }
</style>
@endsection
