@extends('layouts.teacher')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">✏️ Edit Trivia Question</h2>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form id="edit-trivia-form" action="{{ route('teacher.trivia.update', $trivia->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Category Selection -->
                <div class="mb-3">
                    <label for="category_id" class="form-label fw-bold">📌 Select Category</label>
                    <select class="form-control" name="category_id" id="category_id" required>
                        <option value="" disabled>Select a Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $trivia->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
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
                                <option value="{{ $g }}" {{ $trivia->grade_level == $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="difficulty" class="form-label fw-bold">⚙️ Difficulty</label>
                        <select name="difficulty" id="difficulty" class="form-control" required>
                            <option value="">-- Select Difficulty --</option>
                            @foreach($difficultyOptions as $d)
                                <option value="{{ $d }}" {{ $trivia->difficulty == $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Image Input -->
                <div class="form-group mb-3">
                    <label>Upload Image (Optional):</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                @if(isset($trivia) && $trivia->image)
                    <div class="mt-2 mb-3">
                        <p>Current Image:</p>
                        <img src="{{ asset('storage/' . $trivia->image) }}" width="150">
                    </div>
                @endif

                <!-- Question Input -->
                <div class="mb-3">
                    <label for="question" class="form-label fw-bold">❓ Trivia Question</label>
                    <input type="text" class="form-control" name="question" id="question" value="{{ $trivia->question }}" required>
                </div>

                <!-- Answer Options -->
                <div class="mb-3">
                    <label class="form-label fw-bold">🔢 Answer Options</label>
                    <div id="options-container">
                        @foreach($trivia->options ?? [] as $option)
                            <div class="input-group mb-2 option-item">
                                <input type="text" class="form-control option-input" name="options[]" value="{{ $option }}" required>
                                <button type="button" class="btn btn-warning" onclick="removeOption(this)">❌</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addOption()">➕ Add Option</button>
                </div>

                <!-- Correct Answer Selection -->
                <div class="mb-3">
                    <label for="correct_answer" class="form-label fw-bold">✅ Correct Answer</label>
                    <select class="form-control" name="correct_answer" id="correct-answer" required>
                        <option value="">Select Correct Answer</option>
                        @foreach($trivia->options ?? [] as $option)
                            <option value="{{ $option }}" {{ $trivia->correct_answer == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- History / Explanation -->
                <div class="mb-3">
                    <label for="history" class="form-label fw-bold">💡 Did You Know?</label>
                    <textarea class="form-control" name="history" id="history" rows="3"
                        placeholder="Write a fun fact or explanation about the correct answer...">{{ $trivia->history }}</textarea>
                </div>
                
                <!-- Action Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('teacher.trivia.index') }}" class="btn btn-danger px-4">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="button" class="btn btn-success px-4" onclick="confirmUpdate()">
                        <i class="fas fa-save"></i> Update Trivia
                    </button>
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
    let options = document.querySelectorAll('.option-input');

    if (options.length >= 4) {
        Swal.fire({ icon: 'error', title: 'Limit Reached', text: 'You can only add up to 4 options.' });
        return;
    }

    let div = document.createElement('div');
    div.classList.add('input-group', 'mb-2', 'option-item');

    let input = document.createElement('input');
    input.type = 'text';
    input.name = 'options[]';
    input.classList.add('form-control', 'option-input');
    input.required = true;
    input.oninput = validateOptions;

    let button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger');
    button.innerText = '❌';
    button.onclick = function() { removeOption(button); };

    div.appendChild(input);
    div.appendChild(button);
    container.appendChild(div);

    validateOptions();
}

function removeOption(button) {
    let options = document.querySelectorAll('.option-item');

    if (options.length > 3) {
        button.parentElement.remove();
        validateOptions();
    } else {
        Swal.fire({ icon: 'error', title: 'Minimum Required Options', text: 'At least three options are required.' });
    }
}

function validateOptions() {
    let options = document.querySelectorAll('.option-input');
    let correctAnswerDropdown = document.getElementById('correct-answer');
    let values = [];
    let hasDuplicate = false;

    options.forEach(option => {
        let value = option.value.trim();
        if (value !== "") {
            if (values.includes(value)) hasDuplicate = true;
            else values.push(value);
        }
    });

    if (hasDuplicate) Swal.fire({ icon: 'warning', title: 'Duplicate Option!', text: 'Duplicate options are not allowed.' });

    correctAnswerDropdown.innerHTML = '<option value="">Select Correct Answer</option>';
    values.forEach(value => {
        let newOption = document.createElement('option');
        newOption.value = value;
        newOption.textContent = value;
        correctAnswerDropdown.appendChild(newOption);
    });

    correctAnswerDropdown.disabled = values.length < 3;

    let selectedValue = correctAnswerDropdown.value;
    if (!values.includes(selectedValue)) correctAnswerDropdown.value = "";
}

function confirmUpdate() {
    let category = document.getElementById('category_id').value;
    let grade = document.getElementById('grade_level').value;
    let diff = document.getElementById('difficulty').value;
    let correctAnswer = document.getElementById('correct-answer').value;
    let options = document.querySelectorAll('.option-input');
    let values = [];

    if (category === "") {
        Swal.fire({ icon: 'error', title: 'Missing Category', text: 'Please select a category before updating.' });
        return;
    }

    if (grade === "") {
        Swal.fire({ icon: 'error', title: 'Missing Grade Level', text: 'Please select a grade level.' });
        return;
    }

    if (diff === "") {
        Swal.fire({ icon: 'error', title: 'Missing Difficulty', text: 'Please select a difficulty.' });
        return;
    }

    if (options.length < 3) {
        Swal.fire({ icon: 'error', title: 'Not Enough Options', text: 'You must provide at least 3 options.' });
        return;
    }

    for (let option of options) {
        let value = option.value.trim();
        if (value === "") continue;
        if (values.includes(value)) {
            Swal.fire({ icon: 'error', title: 'Duplicate Option!', text: 'Duplicate options are not allowed.' });
            return;
        }
        values.push(value);
    }

    if (correctAnswer === "") {
        Swal.fire({ icon: 'error', title: 'Missing Correct Answer', text: 'Please select a correct answer before updating.' });
        return;
    }

    Swal.fire({
        title: 'Confirm Update',
        text: 'Are you sure you want to update this trivia?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Update it!',
    }).then((result) => {
        if (result.isConfirmed) document.getElementById('edit-trivia-form').submit();
    });
}
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
