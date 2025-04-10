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

            // Incident Type - Cleaning-related incident types
            $table->enum('incident_type', [
                'Equipment Issues',
                'Safety Hazards',
                'Workplace Injuries',
                'Environmental Concerns',
                'Cleaning and Hygiene Issues',
                'Material/Inventory Issues',
                'Facility Maintenance Issues',
                'Unauthorized Access or Vandalism'
            ])->default('Equipment Issues');

            // Impact & Urgency
            $table->enum('impact', ['Low', 'Medium', 'High'])->default('Low');
            $table->enum('urgency', ['Low', 'Medium', 'High'])->default('Low');

            // Priority is determined by Impact & Urgency
            $table->enum('priority', ['Low', 'Medium', 'High'])->nullable();

            // Category specific to cleaning-related incidents
            $table->enum('category', [
                'Broken Equipment', 'Missing Tools', 'Faulty Equipment',
                'Slips, Trips, and Falls', 'Chemical Spills', 'Electrical Hazards',
                'Fire Hazards', 'Cuts and Abrasions', 'Burns', 'Back Strains',
                'Respiratory Issues', 'Water Leaks', 'Excessive Dust',
                'Unpleasant Odors', 'Poorly Cleaned Areas', 'Trash Overflow',
                'Stains and Spills', 'Unhygienic Conditions', 'Shortage of Cleaning Supplies',
                'Expired Products', 'Damaged Storage', 'Broken Fixtures', 'Clogged Drains',
                'Broken Windows or Doors', 'Unauthorized Access', 'Vandalism'
            ])->default('Broken Equipment');

            // Status of the incident
            $table->enum('status', ['Open', 'In Progress', 'Resolved'])->default('Open');

            // Corrective action taken
            $table->text('corrective_action')->nullable();

            // Assigned department (relevant to cleaning)
            $table->enum('assigned_department', ['Cleaning', 'Maintenance', 'Management'])->default('Cleaning');

            // Added photo field for uploading incident images
            $table->string('photo')->nullable();  // To store the path of the uploaded image

            // Timestamps
            $table->timestamps();

            // Foreign key reference to the users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('incidents');
    }
};
