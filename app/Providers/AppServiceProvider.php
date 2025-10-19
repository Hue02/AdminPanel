<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Admin;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Gate::define('admin', function (Admin $user) {
            return $user->isAdmin();
        });

        Gate::define('teacher', function (Admin $user) {
            return $user->isTeacher();
        });

        Auth::viaRequest('admin', function ($request) {
            return Auth::guard('admin')->user();
        });
    }
}
