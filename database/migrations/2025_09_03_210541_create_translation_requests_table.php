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
        Schema::create(
            'translations',
            function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('title');
                $table->text('description');
                $table->string('source_language', 5)->default('en');
                $table->string('target_language', 5)->default('es');
                $table->json('original_content');
                $table->json('translated_content')->nullable();
                $table->string('status');
                $table->text('error_message')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();

                $table->index(['status', 'created_at']);
                $table->index('target_language');
            }
        );
    }
};
