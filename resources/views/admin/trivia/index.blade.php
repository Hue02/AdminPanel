@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header & Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary text-center text-md-start"><i class="fas fa-question-circle"></i> Trivia Questions</h2>

        <div class="d-flex flex-column flex-md-row gap-2">
            <!-- CSV Upload -->
            <form action="{{ route('admin.trivia.upload') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column flex-sm-row gap-2">
                @csrf
                <input type="file" name="csv_file" accept=".csv" required class="form-control form-control-sm w-100">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-upload"></i> Upload CSV
                </button>
            </form>

            <!-- Add New Trivia -->
            <a href="{{ route('admin.trivia.create') }}" class="btn btn-success btn-sm">
                <i class=" fas fa-plus-circle"></i> Add New
            </a>
        </div>
    </div>

    <!-- Flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="row mb-3 g-2">
        <div class="col-md-6">
            <input type="text" id="search-trivia" class="form-control" placeholder="Search trivia..." value="{{ $filters['search'] ?? '' }}">
        </div>
        <div class="col-md-3">
            <select id="filter-grade" class="form-control">
                <option value="">All Grades</option>
                @foreach(['7','8','9','10'] as $g)
                    <option value="{{ $g }}" {{ (isset($filters['grade_level']) && $filters['grade_level'] == $g) ? 'selected' : '' }}>Grade {{ $g }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select id="filter-difficulty" class="form-control">
                <option value="">All Difficulties</option>
                @foreach(['easy','medium','hard'] as $d)
                    <option value="{{ $d }}" {{ (isset($filters['difficulty']) && $filters['difficulty'] == $d) ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="trivia-list">
        @if($trivias->isEmpty())
            <div class="alert alert-warning text-center">
                <h4 class="fw-bold">⚠️ No trivia questions found!</h4>
                <p>Try searching with different keywords or filters.</p>
                <button class="btn btn-secondary mt-2" onclick="resetSearch()">
                    <i class="fas fa-sync-alt"></i> Reset Search
                </button>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                @foreach($trivias as $trivia)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100 d-flex flex-column">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-dark">
                                <i class="fas fa-question-circle"></i> {{ $trivia->question }}
                            </h5>

                            <p class="mb-2">
                                <strong class="text-muted">Category:</strong> 
                                <span class="badge bg-info">{{ $trivia->category->name ?? 'Uncategorized' }}</span>
                            </p>

                            <!-- Grade & Difficulty badges -->
                            <p class="mb-2">
                                <strong class="text-muted">Grade / Difficulty:</strong>
                                <span class="badge bg-primary me-1">Grade {{ $trivia->grade_level }}</span>
                                <span class="badge bg-secondary">{{ ucfirst($trivia->difficulty) }}</span>
                            </p>

                            <div class="mb-3">
                                <strong class="text-muted">Options:</strong>
                                <div class="d-flex flex-wrap">
                                    @php
                                        $options = is_array($trivia->options) ? $trivia->options : json_decode($trivia->options, true);
                                    @endphp
                                    @foreach($options as $option)
                                        <span class="badge bg-light text-dark border me-1 mb-1">{{ $option }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <p>
                                <strong class="text-muted">Correct Answer:</strong> 
                                <span class="badge bg-success">{{ $trivia->correct_answer }}</span>
                            </p>

                            @if(!empty($trivia->history))
                                <p class="mt-2">
                                    <strong class="text-muted">History:</strong> 
                                    <span class="badge bg-dark">{{ $trivia->history }}</span>
                                </p>
                            @endif

                            <div class="mt-auto d-flex justify-content-between">
                                <a href="{{ route('admin.trivia.edit', $trivia->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $trivia->id }})">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                                <form id="delete-form-{{ $trivia->id }}" action="{{ route('admin.trivia.destroy', $trivia->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        @if ($trivias->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $trivias->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    </div>

    <a href="{{ route('admin.trivia.create') }}" class="btn btn-success rounded-circle shadow-lg d-md-none position-fixed"
       style="bottom: 80px; right: 20px; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center; z-index: 1050;">
        <i class="fas fa-plus fa-lg text-white"></i>
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function searchTrivia() {
    let searchQuery = document.getElementById('search-trivia').value;
    let grade = document.getElementById('filter-grade').value;
    let diff = document.getElementById('filter-difficulty').value;

    let url = "{{ route('admin.trivia.index') }}?search=" + encodeURIComponent(searchQuery);
    if (grade) url += "&grade_level=" + encodeURIComponent(grade);
    if (diff) url += "&difficulty=" + encodeURIComponent(diff);
    window.location.href = url;
}

document.getElementById('search-trivia')?.addEventListener('keypress', function(e){
    if(e.key === 'Enter') searchTrivia();
});

document.getElementById('filter-grade')?.addEventListener('change', function(){ searchTrivia(); });
document.getElementById('filter-difficulty')?.addEventListener('change', function(){ searchTrivia(); });

function resetSearch() {
    window.location.href = "{{ route('admin.trivia.index') }}";
}

function confirmDelete(triviaId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the trivia question!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + triviaId).submit();
        }
    });
}
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
