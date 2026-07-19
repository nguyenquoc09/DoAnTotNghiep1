<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->string('patient_code', 20)->nullable()->unique();
            $table->string('full_name', 150)->index();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable()->index();
            $table->string('phone', 20)->index();
            $table->string('email')->nullable()->index();
            $table->string('identity_number', 30)->nullable()->unique();
            $table->string('health_insurance_number', 30)->nullable();
            $table->string('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->string('blood_type', 5)->nullable();
            $table->text('allergies')->nullable();
            $table->text('medical_history')->nullable();
            $table->string('status', 20)->default('active')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() { Schema::dropIfExists('patients'); }
}
