<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CreateJobsTable Migration
 * 
 * Defines the schema for job listings.
 * A Job belongs to a Company (many-to-one relationship).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('requirements');
            $table->string('location');
            $table->enum('type', ['full-time', 'part-time', 'contract', 'internship', 'remote']);
            $table->string('salary_range')->nullable();
            $table->string('category');
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->date('deadline')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
