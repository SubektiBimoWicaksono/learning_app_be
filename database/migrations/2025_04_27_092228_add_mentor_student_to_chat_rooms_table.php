<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->foreignId('mentor_id')->after('id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('student_id')->after('mentor_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->dropForeign(['mentor_id']);
            $table->dropForeign(['student_id']);
            $table->dropColumn(['mentor_id', 'student_id']);
        });
    }
};
