<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trivia extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'options',
        'correct_answer',
        'category_id',
        'history',
        'image',
        'grade_level',   // added
        'difficulty',    // added

    ];


    protected $casts = [
        'options' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
