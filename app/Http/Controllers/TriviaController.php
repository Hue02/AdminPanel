<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trivia;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TriviaController extends Controller
{
    // allowed values
    protected $grades = ['7','8','9','10'];
    protected $difficulties = ['easy','medium','hard'];

    public function index(Request $request)
    {
        $query = Trivia::with('category');

        // search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('question', 'like', "%$searchTerm%")
                    ->orWhere('correct_answer', 'like', "%$searchTerm%")
                    ->orWhereHas('category', function ($catQuery) use ($searchTerm) {
                        $catQuery->where('name', 'like', "%$searchTerm%");
                    })
                    ->orWhereRaw("JSON_CONTAINS(options, '\"$searchTerm\"')");
            });
        }

        // filter by grade_level
        if ($request->filled('grade_level') && in_array($request->grade_level, $this->grades)) {
            $query->where('grade_level', $request->grade_level);
        }

        // filter by difficulty
        if ($request->filled('difficulty') && in_array(strtolower($request->difficulty), $this->difficulties)) {
            $query->where('difficulty', strtolower($request->difficulty));
        }

        $trivias = $query->orderBy('id', 'desc')->paginate(6)->withQueryString();

        $routePrefix = request()->is('teacher/*') ? 'teacher' : 'admin';
        // pass current filters so views can show selected values
        $filters = [
            'search' => $request->search ?? null,
            'grade_level' => $request->grade_level ?? null,
            'difficulty' => $request->difficulty ?? null,
        ];
        return view("$routePrefix.trivia.index", compact('trivias','filters'));
    }

    public function uploadCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048'
        ]);

        try {
            $file = $request->file('csv_file');
            $handle = fopen($file, "r");

            // Skip header row
            $header = fgetcsv($handle);
            $skippedQuestions = []; // Store skipped questions
            $rowNum = 1;

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $rowNum++;

                // Expect exactly at least 7 columns: question,category,options,correct_answer,history,grade_level,difficulty
                if (count($data) < 7) {
                    // skip or log — strict format required
                    continue;
                }

                $question = trim($data[0]);
                $categoryName = trim($data[1]);
                $options = array_map('trim', explode('|', $data[2]));
                $correctAnswer = trim($data[3]);
                $history = trim($data[4]);
                $gradeLevel = trim($data[5]);
                $difficulty = strtolower(trim($data[6]));

                // validate grade & difficulty
                if (!in_array($gradeLevel, $this->grades) || !in_array($difficulty, $this->difficulties)) {
                    // skip invalid row
                    $skippedQuestions[] = $question . " (invalid grade/difficulty on row $rowNum)";
                    continue;
                }

                // Check for duplicate
                if (Trivia::where('question', $question)->exists()) {
                    $skippedQuestions[] = $question . " (duplicate)";
                    continue;
                }

                // Get or create category
                $category = Category::firstOrCreate(['name' => $categoryName]);

                Trivia::create([
                    'question' => $question,
                    'category_id' => $category->id,
                    'options' => $options,
                    'correct_answer' => $correctAnswer,
                    'history' => $history,
                    'grade_level' => $gradeLevel,
                    'difficulty' => $difficulty,
                ]);
            }

            fclose($handle);

            $message = 'Trivia questions uploaded successfully!';
            if (!empty($skippedQuestions)) {
                $message .= ' Some rows were skipped: ' . implode('; ', $skippedQuestions);
            }

            $redirectRoute = Auth::user()->isAdmin() ? 'admin.trivia.index' : 'teacher.trivia.index';
            return redirect()->route($redirectRoute)->with('success', $message);

        } catch (\Exception $e) {
            $redirectRoute = Auth::user()->isAdmin() ? 'admin.trivia.index' : 'teacher.trivia.index';
            return redirect()->route($redirectRoute)->with('error', 'Error processing CSV file: ' . $e->getMessage());
        }
    }

    public function updateFromCSV(Request $request)
    {
        $request->validate([
            'csv_update_file' => 'required|mimes:csv,txt|max:2048'
        ]);

        try {
            $file = $request->file('csv_update_file');
            $handle = fopen($file, "r");

            // Skip header row
            $header = fgetcsv($handle);

            $updatedCount = 0;
            $notFoundCount = 0;
            $skipped = [];

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($data) < 7) {
                    continue; // Skip invalid rows
                }

                $question = trim($data[0]);
                $categoryName = trim($data[1]);
                $options = array_map('trim', explode('|', $data[2]));
                $correctAnswer = trim($data[3]);
                $history = trim($data[4]);
                $gradeLevel = trim($data[5]);
                $difficulty = strtolower(trim($data[6]));

                $trivia = Trivia::where('question', $question)->first();

                if ($trivia) {
                    // validate grade/difficulty
                    if (!in_array($gradeLevel, $this->grades) || !in_array($difficulty, $this->difficulties)) {
                        $skipped[] = $question . " (invalid grade/difficulty)";
                        continue;
                    }

                    $category = Category::firstOrCreate(['name' => $categoryName]);

                    $trivia->update([
                        'category_id' => $category->id,
                        'options' => $options,
                        'correct_answer' => $correctAnswer,
                        'history' => $history,
                        'grade_level' => $gradeLevel,
                        'difficulty' => $difficulty,
                    ]);

                    $updatedCount++;
                } else {
                    $notFoundCount++;
                }
            }

            fclose($handle);

            $message = $updatedCount > 0
                ? "$updatedCount trivia questions updated successfully! $notFoundCount questions not found."
                : "No trivia questions were updated. $notFoundCount questions not found.";

            if (!empty($skipped)) {
                $message .= ' Some rows skipped: ' . implode('; ', $skipped);
            }

            $redirectRoute = Auth::user()->isAdmin() ? 'admin.trivia.index' : 'teacher.trivia.index';
            return redirect()->route($redirectRoute)->with($updatedCount > 0 ? 'success' : 'error', $message);

        } catch (\Exception $e) {
            $redirectRoute = Auth::user()->isAdmin() ? 'admin.trivia.index' : 'teacher.trivia.index';
            return redirect()->route($redirectRoute)->with('error', 'Error processing CSV file: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $categories = Category::all();
        $routePrefix = request()->is('teacher/*') ? 'teacher' : 'admin';

        // pass grade and difficulty options for blade convenience
        $gradeOptions = $this->grades;
        $difficultyOptions = $this->difficulties;

        return view("$routePrefix.trivia.create", compact('categories','gradeOptions','difficultyOptions'));
    }

    public function edit($id)
    {
        $trivia = Trivia::findOrFail($id);
        $categories = Category::all();
        $routePrefix = request()->is('teacher/*') ? 'teacher' : 'admin';

        $gradeOptions = $this->grades;
        $difficultyOptions = $this->difficulties;

        return view("$routePrefix.trivia.edit", compact('trivia', 'categories','gradeOptions','difficultyOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'correct_answer' => 'required|string|in:' . implode(',', $request->options),
            'category_id' => 'required|exists:categories,id',
            'grade_level' => 'required|in:7,8,9,10',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('trivia_images', 'public');
        }

        Trivia::create([
            'question' => $request->question,
            'options' => $request->options,
            'correct_answer' => $request->correct_answer,
            'category_id' => $request->category_id,
            'history' => $request->history,
            'image' => $imagePath ?? null,
            'grade_level' => $request->grade_level,
            'difficulty' => strtolower($request->difficulty),
        ]);

        $routePrefix = request()->is('teacher/*') ? 'teacher' : 'admin';
        return redirect()->route("$routePrefix.trivia.index")->with('success', 'Trivia added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'correct_answer' => 'required|string|in:' . implode(',', $request->options),
            'category_id' => 'required|exists:categories,id',
            'grade_level' => 'required|in:7,8,9,10',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        $trivia = Trivia::findOrFail($id);

        // Image handling
        if ($request->hasFile('image')) {
            if (!empty($trivia->image) && \Storage::disk('public')->exists($trivia->image)) {
                \Storage::disk('public')->delete($trivia->image);
            }
            $imagePath = $request->file('image')->store('trivia_images', 'public');
        } else {
            $imagePath = $trivia->image;
        }

        $trivia->update([
            'question' => $request->question,
            'options' => $request->options,
            'correct_answer' => $request->correct_answer,
            'category_id' => $request->category_id,
            'history' => $request->history,
            'image' => $imagePath,
            'grade_level' => $request->grade_level,
            'difficulty' => strtolower($request->difficulty),
        ]);

        $routePrefix = request()->is('teacher/*') ? 'teacher' : 'admin';
        return redirect()->route("$routePrefix.trivia.index")->with('success', 'Trivia updated successfully.');
    }

    public function destroy($id)
    {
        $trivia = Trivia::findOrFail($id);
        $trivia->delete();

        $routePrefix = request()->is('teacher/*') ? 'teacher' : 'admin';
        return redirect()->route("$routePrefix.trivia.index")->with('success', 'Trivia deleted successfully.');
    }
}
