<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CreateUsersTable Migration
 * 
 * Defines the database schema for the users table.
 * Follows OOP migration class pattern as required by Laravel MVC.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the users table with all necessary columns.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['jobseeker', 'company', 'admin'])->default('jobseeker');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Drops the users table.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
