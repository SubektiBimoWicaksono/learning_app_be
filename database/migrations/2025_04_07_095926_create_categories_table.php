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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
             // Add dummy categories after table creation
             DB::table('categories')->insert([
                ['name' => 'Akutansi Keuangan'],
                ['name' => 'Audit dan Assurance'],
                ['name' => 'Perpajakan'],
                ['name' => 'Akutansi Sektor Publik'],
                ['name' => 'Etika Hukum dan Profesi'],
                ['name' => 'Keuangan dan Analisis'],
                ['name' => 'Sistem Informasi Akutansi'],
                ['name' => 'Akutansi Manajerial/ Biaya'],
            ]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
