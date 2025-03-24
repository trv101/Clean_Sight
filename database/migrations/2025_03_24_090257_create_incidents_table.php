<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('description');

            // IT or HR Incident Type
            $table->enum('incident_type', ['IT', 'HR'])->default('IT');

            // Impact & Urgency (Instead of manual Priority selection)
            $table->enum('impact', ['Low', 'Medium', 'High'])->default('Low');
            $table->enum('urgency', ['Low', 'Medium', 'High'])->default('Low');

            // Priority is now determined by Impact & Urgency
            $table->enum('priority', ['Low', 'Medium', 'High'])->nullable();

            // Category (IT + HR)
            $table->enum('category', [
                'Network', 'Hardware', 'Software', 'Security', 'Other', // IT
                'Workplace Conflict', 'Harassment', 'Policy Violation', 'Unauthorized Entry', 'Theft', 'Emergency', 'Other HR'
            ])->default('Other');

            $table->enum('status', ['Open', 'In Progress', 'Resolved'])->default('Open');
            $table->text('corrective_action')->nullable();
            $table->enum('assigned_department', ['IT', 'HR'])->default('IT');

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
