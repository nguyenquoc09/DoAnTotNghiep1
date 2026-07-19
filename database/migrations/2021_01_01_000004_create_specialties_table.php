<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialtiesTable extends Migration
{
    public function up()
    {
        Schema::create('specialties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('status', 20)->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() { Schema::dropIfExists('specialties'); }
}
