<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('submission_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained(
                table: 'submissions', 
                indexName: 'submisions_themes_submission_id'
            );
            $table->foreignId('theme_id')->constrained(
                table: 'themes', 
                indexName: 'submisions_themes_theme_id'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_themes');
    }
};
