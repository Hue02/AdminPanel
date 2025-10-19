<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('email', 'LIKE', "%{$searchTerm}%");
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        if (request()->is('admin/*')) {
            return view('admin.users.index', compact('users'));
        } elseif (request()->is('teacher/*')) {
            return view('teacher.users.index', compact('users'));
        }
    }

    public function create()
    {
        if (request()->is('admin/*')) {
            return view('admin.users.create');
        } elseif (request()->is('teacher/*')) {
            return view('teacher.users.create');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'grade_level' => 'required|in:7,8,9,10',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'grade_level' => $request->grade_level,
        ]);

        // Create default user_progress (reset defaults)
        UserProgress::create([
            'user_id' => $user->id,
            'level' => 1,
            'math_level' => 1,
            'science_level' => 1,
            'general_level' => 1,
            'coins' => 0,
            'correct_answers' => 0,
            'incorrect_answers' => 0,
            'keys' => 0,
            'completion_time' => null,
        ]);

        if (request()->is('admin/*')) {
            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
        } elseif (request()->is('teacher/*')) {
            return redirect()->route('teacher.users.index')->with('success', 'User created successfully.');
        }
    }

    public function edit(User $user)
    {
        if (request()->is('admin/*')) {
            return view('admin.users.edit', compact('user'));
        } elseif (request()->is('teacher/*')) {
            return view('teacher.users.edit', compact('user'));
        }
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'grade_level' => 'required|in:7,8,9,10',
        ]);

        $oldGrade = $user->grade_level;
        $newGrade = $request->grade_level;

        // Update user basic info
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'grade_level' => $newGrade,
        ]);

        // If grade changed, reset user_progress to defaults
        if ($oldGrade !== $newGrade) {
            $progress = UserProgress::firstOrCreate(['user_id' => $user->id]);

            $progress->level = 1;
            $progress->math_level = 1;
            $progress->science_level = 1;
            $progress->general_level = 1;
            $progress->coins = 0;
            $progress->correct_answers = 0;
            $progress->incorrect_answers = 0;
            $progress->keys = 0;
            $progress->completion_time = null;
            $progress->save();
        }

        if (request()->is('admin/*')) {
            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        } elseif (request()->is('teacher/*')) {
            return redirect()->route('teacher.users.index')->with('success', 'User updated successfully.');
        }
    }

    public function destroy(User $user)
    {
        $user->delete();

        if (request()->is('admin/*')) {
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } elseif (request()->is('teacher/*')) {
            return redirect()->route('teacher.users.index')->with('success', 'User deleted successfully.');
        }
    }
}
