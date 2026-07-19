<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_code', 20)->nullable()->unique();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('specialty_id')->constrained()->restrictOnDelete();
            $table->foreignId('doctor_id')->constrained()->restrictOnDelete();
            $table->foreignId('doctor_schedule_id')->constrained()->restrictOnDelete();
            $table->foreignId('medical_service_id')->nullable()->constrained()->nullOnDelete();
            $table->date('appointment_date')->index();
            $table->time('appointment_time');
            $table->text('reason');
            $table->text('symptoms_note')->nullable();
            $table->string('booking_source', 20)->default('online');
            $table->string('status', 20)->default('pending')->index();
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['doctor_id', 'appointment_date', 'appointment_time'], 'appointment_slot_index');
        });
    }

    public function down() { Schema::dropIfExists('appointments'); }
}
