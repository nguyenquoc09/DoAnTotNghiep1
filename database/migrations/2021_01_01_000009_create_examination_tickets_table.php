<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminationTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('examination_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code', 20)->nullable()->unique();
            $table->foreignId('appointment_id')->unique()->constrained()->restrictOnDelete();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('doctor_id')->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('queue_number');
            $table->date('examination_date')->index();
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('status', 20)->default('waiting')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['doctor_id', 'examination_date', 'queue_number'], 'daily_doctor_queue_unique');
        });
    }

    public function down() { Schema::dropIfExists('examination_tickets'); }
}
