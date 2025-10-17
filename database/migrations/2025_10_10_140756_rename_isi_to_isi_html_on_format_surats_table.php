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
        Schema::table('format_surats', function (Blueprint $table) {
            $table->renameColumn('isi', 'isi_html');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('format_surats', function (Blueprint $table) {
            $table->renameColumn('isi_html', 'isi');
        });
    }
};
