<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('margas', function (Blueprint $table) {
            $table->id();
            $table->string('wilayah_adat'); // Wilayah adat
            $table->string('suku');         // Suku
            $table->string('marga');        // Nama Marga
            $table->string('berkas')->nullable(); // Path file pendukung
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('margas');
    }
};
