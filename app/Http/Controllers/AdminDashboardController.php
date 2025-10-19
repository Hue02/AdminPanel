<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trivia;
use App\Models\UserProgress;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'userCount' => User::count(),  
            'triviaCount' => Trivia::count(),
            'reportCount' => UserProgress::count(),
        ]);
    }
}
