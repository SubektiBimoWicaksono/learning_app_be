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
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->date('dob')->nullable()->change();
            $table->string('no_telp')->nullable()->change();
            $table->enum('gender', ['male', 'female'])->nullable()->change();
            $table->enum('role', ['mahasiswa', 'mentor', 'admin'])->nullable()->change();
            $table->string('pin')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->date('dob')->nullable(false)->change();
            $table->string('no_telp')->nullable(false)->change();
            $table->enum('gender', ['male', 'female'])->nullable(false)->change();
            $table->enum('role', ['mahasiswa', 'mentor', 'admin'])->nullable(false)->change();
            $table->string('pin')->nullable(false)->change();
        });
    }
};
