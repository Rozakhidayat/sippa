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
        Schema::create('development_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('type_development');
            $table->string('task_name');
            $table->string('sort_order');
            $table->foreignId('role_id')
                ->constrained('roles')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('development_tasks');
    }
};
