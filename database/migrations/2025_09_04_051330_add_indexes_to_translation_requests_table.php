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
        Schema::table(
            'translations',
            function (Blueprint $table) {
                $table->index(['status', 'created_at'], 'idx_status_created');
                $table->index('target_language', 'idx_target_language');
                $table->index('processed_at', 'idx_processed_at');
            }
        );
    }
};
