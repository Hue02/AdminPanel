<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trivia', function (Blueprint $table) {
            $table->json('options')->after('question'); // Stores multiple choices
        });
    }

    public function down(): void
    {
        Schema::table('trivia', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
};
