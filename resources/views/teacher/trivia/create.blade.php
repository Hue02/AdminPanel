@extends('layouts.teacher')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">📝 Add Trivia Question</h2>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form id="trivia-form" action="{{ route('teacher.trivia.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Category Selection -->
                <div class="mb-3">
                    <label for="category_id" class="form-label fw-bold">📌 Select Category</label>
                    <select class="form-control" name="category_id" id="category_id" required>
                        <option value="" selected disabled>Choose a Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Grade & Difficulty side-by-side -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="grade_level" class="form-label fw-bold">🎓 Grade Level</label>
                        <select name="grade_level" id="grade_level" class="form-control" required>
                            <option value="">-- Select Grade Level --</option>
                            @foreach($gradeOptions as $g)
                                <option value="{{ $g }}">{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="difficulty" class="form-label fw-bold">⚙️ Difficulty</label>
                        <select name="difficulty" id="difficulty" class="form-control" required>
                            <option value="">-- Select Difficulty --</option>
                            @foreach($difficultyOptions as $d)
                                <option value="{{ $d }}">{{ ucfirst($d) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Image Input -->
                <div class="form-group mb-3">
                    <label>Upload Image (Optional):</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <!-- Question Input -->
                <div class="mb-3">
                    <label for="question" class="form-label fw-bold">❓ Trivia Question</label>
                    <input type="text" class="form-control" name="question" id="question" placeholder="Enter your question here" required>
                </div>

                <!-- Options Input -->
                <div class="mb-3">
                    <label class="form-label fw-bold">🔢 Answer Options</label>
                    <div id="options-container">
                        <input type="text" class="form-control mb-2" name="options[]" placeholder="Option 1" oninput="validateOptions()" required>
                        <input type="text" class="form-control mb-2" name="options[]" placeholder="Option 2" oninput="validateOptions()" required>
                        <input type="text" class="form-control mb-2" name="options[]" placeholder="Option 3" oninput="validateOptions()" required>
                    </div>
                    <button type="button" id="add-option-btn" class="btn btn-sm btn-secondary mt-2" onclick="addOption()">
                        ➕ Add Option
                    </button>
                </div>

                <!-- Correct Answer Selection -->
                <div class="mb-3">
                    <label for="correct_answer" class="form-label fw-bold">✅ Correct Answer</label>
                    <select class="form-control" name="correct_answer" id="correct-answer" required disabled>
                        <option value="">Select Correct Answer</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="history" class="form-label fw-bold">💡 Did You Know?</label>
                    <textarea class="form-control" name="history" id="history" rows="3"
                        placeholder="Write a fun fact or explanation about the correct answer..."></textarea>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="button" class="btn btn-success px-4" onclick="confirmSubmission()">
                        <i class="fas fa-save"></i> Save Trivia
                    </button>
                    <a href="{{ route('teacher.trivia.index') }}" class="btn btn-danger px-4">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert and JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function addOption() {
    let container = document.getElementById('options-container');
    let options = document.querySelectorAll('input[name="options[]"]');

    if (options.length >= 4) {
        Swal.fire({
            icon: 'error',
            title: 'Limit Reached',
            text: 'You can only add up to 4 options.',
        });
        return;
    }

    let input = document.createElement('input');
    input.type = 'text';
    input.name = 'options[]';
    input.classList.add('form-control', 'mb-2');
    input.placeholder = `Option ${options.length + 1}`;
    input.required = true;
    input.oninput = validateOptions;
    container.appendChild(input);

    validateOptions();
}

function validateOptions() {
    let options = document.querySelectorAll('input[name="options[]"]');
    let correctAnswerDropdown = document.getElementById('correct-answer');
    let values = [];
    let hasDuplicate = false;

    options.forEach(option => {
        let value = option.value.trim();
        if (value !== "") {
            if (values.includes(value)) {
                hasDuplicate = true;
            } else {
                values.push(value);
            }
        }
    });

    if (hasDuplicate) {
        Swal.fire({
            icon: 'warning',
            title: 'Duplicate Option!',
            text: 'Duplicate options are not allowed.',
        });
    }

    correctAnswerDropdown.innerHTML = '<option value="">Select Correct Answer</option>';
    values.forEach(value => {
        let newOption = document.createElement('option');
        newOption.value = value;
        newOption.textContent = value;
        correctAnswerDropdown.appendChild(newOption);
    });

    correctAnswerDropdown.disabled = values.length < 3;
}

function confirmSubmission() {
    let category = document.getElementById('category_id').value;
    let grade = document.getElementById('grade_level').value;
    let diff = document.getElementById('difficulty').value;
    let correctAnswer = document.getElementById('correct-answer').value;
    let options = document.querySelectorAll('input[name="options[]"]');
    let values = [];

    if (category === "") {
        Swal.fire({
            icon: 'error',
            title: 'Missing Category',
            text: 'Please select a category before submitting.',
        });
        return;
    }

    if (grade === "") {
        Swal.fire({
            icon: 'error',
            title: 'Missing Grade Level',
            text: 'Please select a grade level.',
        });
        return;
    }

    if (diff === "") {
        Swal.fire({
            icon: 'error',
            title: 'Missing Difficulty',
            text: 'Please select a difficulty.',
        });
        return;
    }

    if (options.length < 3) {
        Swal.fire({
            icon: 'error',
            title: 'Not Enough Options',
            text: 'You must provide at least 3 options.',
        });
        return;
    }

    for (let option of options) {
        let value = option.value.trim();
        if (value === "") continue;
        if (values.includes(value)) {
            Swal.fire({
                icon: 'error',
                title: 'Duplicate Option!',
                text: 'Duplicate options are not allowed.',
            });
            return;
        }
        values.push(value);
    }

    if (correctAnswer === "") {
        Swal.fire({
            icon: 'error',
            title: 'Missing Correct Answer',
            text: 'Please select a correct answer before submitting.',
        });
        return;
    }

    Swal.fire({
        title: 'Confirm Submission',
        text: 'Are you sure you want to save this trivia?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Save it!',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('trivia-form').submit();
        }
    });
}
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
