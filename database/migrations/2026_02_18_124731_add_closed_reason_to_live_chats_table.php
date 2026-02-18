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
        Schema::table('live_chats', function (Blueprint $table) {
            $table->enum('closed_reason', [
                'chat_resolved',
                'converted_to_ticket'
            ])->nullable()->after('status');

            $table->timestamp('closed_at')->nullable()->after('closed_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('live_chats', function (Blueprint $table) {
            $table->dropColumn(['closed_reason', 'closed_at']);
        });
    }
};
