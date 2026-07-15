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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('no_ticket')->unique();
            $table->date('submission_date');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bp_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('departement_id')->constrained('departements')->onDelete('cascade');
            $table->string('type_development');
            $table->string('application_name');
            $table->text('background');
            $table->text('application_scope');
            $table->text('cost_benefit');
            $table->text('risk');
            $table->string('status');
            $table->string('last_remark')->nullable();
            $table->string('previous_state')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
