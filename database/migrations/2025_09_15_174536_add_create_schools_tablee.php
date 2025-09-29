<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('name');
            $table->string('registration_number')->nullable()->unique();
            $table->text('description')->nullable();
            $table->boolean('is_verified')->default(true);

            // Location
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->foreignId('province_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();

            // Contact Info
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // Other info
            $table->integer('student_count')->nullable();
            $table->integer('teacher_count')->nullable();
            $table->string('school_type')->nullable(); // e.g., Primary, High, University
            $table->string('principal_name')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
