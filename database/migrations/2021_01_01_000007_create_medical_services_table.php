<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalServicesTable extends Migration
{
    public function up()
    {
        Schema::create('medical_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialty_id')->nullable()->constrained()->nullOnDelete();
            $table->string('service_code', 20)->unique();
            $table->string('name', 150)->index();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('status', 20)->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() { Schema::dropIfExists('medical_services'); }
}
