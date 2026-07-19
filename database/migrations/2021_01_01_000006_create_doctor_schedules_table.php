<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->restrictOnDelete();
            $table->date('work_date')->index();
            $table->string('shift_name', 50);
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('maximum_patients')->default(8);
            $table->unsignedSmallInteger('booked_patients')->default(0);
            $table->string('room_number', 30)->nullable();
            $table->string('status', 20)->default('active')->index();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->unique(['doctor_id', 'work_date', 'start_time'], 'doctor_schedule_unique');
        });
    }

    public function down() { Schema::dropIfExists('doctor_schedules'); }
}
