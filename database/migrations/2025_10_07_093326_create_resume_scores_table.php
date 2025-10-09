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
        Schema::create('resume_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('jobs')->onDelete('cascade');
            $table->foreignId('resume_id')->constrained('resumes')->onDelete('cascade');
            $table->float('score')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
        Schema::create('resume_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_details')->onDelete('cascade');
            $table->foreignId('resume_id')->constrained('resumes')->onDelete('cascade');
            $table->float('score')->nullable();
            $table->text('feedback')->nullable();
            $table->string('status')->default('completed');
            $table->timestamp('scanned_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_scores');
    }
};
