<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('desc');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('media_full_access')->default(false);
            $table->enum('level', ['beginner', 'intermediate', 'advanced']);
            $table->boolean('audio_book')->default(false);
            $table->boolean('lifetime_access')->default(false);
            $table->boolean('certificate')->default(false);
            $table->string('image')->nullable();
            $table->int('price')->nullable();
            $table->timestamps();
        });
        ;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
