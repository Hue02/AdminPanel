<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trivia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TriviaController extends Controller
{
    public function getRandomQuestion(Request $request): JsonResponse
    {
        $category = $request->query('category');
        $difficulty = $request->query('difficulty');
        $gradeLevel = $request->query('grade_level');

        $query = Trivia::query()->with('category');

        // Filter by category name (case-insensitive)
        if ($category) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->whereRaw('LOWER(name) = ?', [strtolower($category)]);
            });
        }

        // Filter by difficulty (easy|medium|hard)
        if ($difficulty) {
            $difficultyNormalized = strtolower($difficulty);
            $query->where('difficulty', $difficultyNormalized);
        }

        // Filter by grade_level (7|8|9|10)
        if ($gradeLevel) {
            // Accept both numeric or string, ensure it's exactly the stored enum
            $query->where('grade_level', $gradeLevel);
        }

        // Get one random matching trivia
        $trivia = $query->inRandomOrder()->first();

        if (!$trivia) {
            return response()->json(['error' => 'No trivia found for the selected category/grade/difficulty.'], 404);
        }

        return response()->json([
            'id' => $trivia->id,
            'question' => $trivia->question,
            'options' => $trivia->options,
            'correct_answer' => $trivia->correct_answer,
            'history' => $trivia->history,
            'image' => $trivia->image ? asset('storage/' . $trivia->image) : null,
            'category' => $trivia->category ? $trivia->category->name : null,
            'grade_level' => $trivia->grade_level,
            'difficulty' => $trivia->difficulty,
        ]);
    }
}
