<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->string('record_code', 20)->nullable()->unique();
            $table->foreignId('examination_ticket_id')->unique()->constrained()->restrictOnDelete();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('doctor_id')->constrained()->restrictOnDelete();
            $table->text('chief_complaint')->nullable();
            $table->text('current_symptoms')->nullable();
            $table->text('medical_history')->nullable();
            $table->text('family_history')->nullable();
            $table->unsignedSmallInteger('pulse')->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->string('blood_pressure', 20)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('conclusion')->nullable();
            $table->text('treatment_plan')->nullable();
            $table->text('doctor_note')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamps();
        });
    }

    public function down() { Schema::dropIfExists('medical_records'); }
}
