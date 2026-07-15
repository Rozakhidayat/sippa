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
        Schema::create('kompartements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('direktorat_id')->constrained(
                table: 'direktorats', 
                indexName: 'kompartements_direktorat_id'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kompartements');
    }
};
