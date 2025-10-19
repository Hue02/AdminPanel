<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class UserProgressController extends Controller
{
    public function index(Request $request)
    {
        $query = UserProgress::with('user');

        // Search users by name
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Get paginated results for table
        $userProgress = $query->orderBy('level', 'desc')->paginate(10);

        // Get data for charts
        $chartData = $query->select('user_id', 'level', 'coins')
                            ->with('user:id,name') // Fetch only user name
                            ->get();

        return view('admin.reports.index', compact('userProgress', 'chartData'));
    }


    public function getUserCoins(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user_progress,user_id'
        ]);

        $progress = UserProgress::where('user_id', $request->user_id)->first();

        if ($progress) {
            return response()->json([
                'coins' => $progress->coins
            ], 200);
        }

        return response()->json(['message' => 'User progress not found.'], 404);
    }


    public function updateCoins(Request $request)
    {
        Log::info('Received Coin Update:', $request->all());

        $request->validate([
            'user_id' => 'required|exists:user_progress,user_id',
            'coins' => 'required|integer|min:0',
        ]);

        $progress = UserProgress::where('user_id', $request->user_id)->first();

        if ($progress) {
            $progress->coins = $request->coins;
            $progress->save();

            return response()->json([
                'message' => 'User coins updated successfully.',
                'coins' => $progress->coins
            ], 200);
        }

        return response()->json(['message' => 'User progress not found.'], 404);
    }
    
    /**
     * Increment correct and incorrect answers for a user.
     */
    public function incrementAnswers(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user_progress,user_id',
            'subject' => 'required|in:math,science,general',
            'correct' => 'required|integer',
            'incorrect' => 'required|integer',
        ]);

        $progress = UserProgress::firstOrCreate(['user_id' => $request->user_id]);

        $subject = $request->subject; // "math", "science", "general"

        $correctColumn = "{$subject}_correct";
        $incorrectColumn = "{$subject}_incorrect";

        $progress->$correctColumn += $request->correct;
        $progress->$incorrectColumn += $request->incorrect;

        $progress->save();

        return response()->json(['message' => 'Progress updated'], 200);
    }

    /**
     * Update the user's level for a specific category.
     */
    public function updateLevel(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user_progress,user_id',
            'field'   => 'required|string|in:math_level,science_level,general_level',
            'level'   => 'required|integer|min:1',
        ]);

        $progress = UserProgress::where('user_id', $request->user_id)->first();

        if (!$progress) {
            return response()->json(['message' => 'User progress not found.'], 404);
        }

        $field = $request->field;
        $newLevel = $request->level;

        if ($newLevel > $progress->$field) {
            $progress->$field = $newLevel;
            // Update overall level to be the max of all categories
            $progress->level = max($progress->math_level, $progress->science_level, $progress->general_level);
            $progress->save();
        }

        return response()->json([
            'message' => 'User level updated successfully.',
            'data' => $progress
        ]);
    }

    public function getLeaderboard(Request $request)
    {
        try {
            // Get leaderboard with user details
            $leaderboard = UserProgress::select(
                'user_progress.*', 
                'users.name'
            )
            ->join('users', 'user_progress.user_id', '=', 'users.id')
            ->orderBy('coins', 'desc')
            ->orderBy('level', 'desc')
            ->limit(20)
            ->get();


            // If no data is found
            if ($leaderboard->isEmpty()) {
                return response()->json([
                    'message' => 'No leaderboard data found.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => 'Leaderboard retrieved successfully.',
                'data' => $leaderboard
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the leaderboard.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserLevel($userId)
    {
        $progress = UserProgress::where('user_id', $userId)->first();

        if (!$progress) {
            return response()->json(['message' => 'User progress not found'], 404);
        }

        return response()->json([
            'user_id'       => $userId,
            'math_level'    => $progress->math_level,
            'science_level' => $progress->science_level,
            'general_level' => $progress->general_level,
        ]);
    }
    
    public function getUserKeys(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:user_progress,user_id']);
        $progress = UserProgress::where('user_id', $request->user_id)->first();
        return response()->json(['keys' => $progress->keys], 200);
    }

    public function updateKeys(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user_progress,user_id',
            'keys' => 'required|integer|min:0',
        ]);

        $progress = UserProgress::where('user_id', $request->user_id)->first();
        $progress->keys = $request->keys;
        $progress->save();

        return response()->json(['message' => 'Keys updated', 'keys' => $progress->keys], 200);
    }

    public function teacherDashboard()
    {
        // Fetch reports (you can paginate if needed)
        $reports = \App\Models\UserProgress::with('user')
                    ->orderBy('level', 'desc')
                    ->take(10) // show top 10
                    ->get();

        return view('teacher.dashboard', compact('reports'));
    }
}
