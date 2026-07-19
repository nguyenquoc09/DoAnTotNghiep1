<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('prescription_code', 20)->nullable()->unique();
            $table->foreignId('medical_record_id')->unique()->constrained()->restrictOnDelete();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('doctor_id')->constrained()->restrictOnDelete();
            $table->date('prescribed_date');
            $table->text('general_instruction')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamps();
        });
    }

    public function down() { Schema::dropIfExists('prescriptions'); }
}
