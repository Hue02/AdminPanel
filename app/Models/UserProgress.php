<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;

    protected $table = 'user_progress';

    protected $fillable = [
        'user_id',
        'math_correct', 'math_incorrect',
        'science_correct', 'science_incorrect',
        'general_correct', 'general_incorrect',
        'coins', 'keys', 'level'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
