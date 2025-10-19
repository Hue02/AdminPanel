<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'profile_picture', 'role'];

    protected $hidden = ['password'];

    public function getProfilePictureAttribute($value)
    {
        return $value ? asset('storage/' . $value) : asset('default.png');
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }
}
