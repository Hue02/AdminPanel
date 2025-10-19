<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->integer('math_level')->default(1)->after('level');
            $table->integer('science_level')->default(1)->after('math_level');
            $table->integer('general_knowledge_level')->default(1)->after('science_level');
        });
    }

    public function down()
    {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropColumn(['math_level', 'science_level', 'general_knowledge_level']);
        });
    }

};
