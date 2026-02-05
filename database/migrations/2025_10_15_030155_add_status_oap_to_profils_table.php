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
        Schema::table('profils', function (Blueprint $table) {
            if (!Schema::hasColumn('profils', 'status_oap')) {
                $table->boolean('status_oap')->default(false)->after('status');
            }

            if (!Schema::hasColumn('profils', 'marga_terverifikasi')) {
                $table->string('marga_terverifikasi')->nullable()->after('status_oap');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profils', function (Blueprint $table) {
            if (Schema::hasColumn('profils', 'marga_terverifikasi')) {
                $table->dropColumn('marga_terverifikasi');
            }

            if (Schema::hasColumn('profils', 'status_oap')) {
                $table->dropColumn('status_oap');
            }
        });
    }
};
