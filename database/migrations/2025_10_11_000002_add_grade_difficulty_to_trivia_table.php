<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trivia', function (Blueprint $table) {
            $table->enum('grade_level', ['7', '8', '9', '10'])->after('category_id');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->after('grade_level');
        });
    }

    public function down(): void
    {
        Schema::table('trivia', function (Blueprint $table) {
            $table->dropColumn(['grade_level', 'difficulty']);
        });
    }
};
