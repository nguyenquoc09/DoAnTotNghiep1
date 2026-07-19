<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->restrictOnDelete();
            $table->foreignId('specialty_id')->constrained()->restrictOnDelete();
            $table->string('doctor_code', 20)->nullable()->unique();
            $table->string('degree', 100)->nullable();
            $table->string('academic_title', 100)->nullable();
            $table->unsignedTinyInteger('years_of_experience')->default(0);
            $table->text('biography')->nullable();
            $table->decimal('consultation_fee', 12, 2)->default(0);
            $table->string('room_number', 30)->nullable();
            $table->string('status', 20)->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() { Schema::dropIfExists('doctors'); }
}
