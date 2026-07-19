<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('clinic_settings', function (Blueprint $table) {
            $table->id();
            $table->string('clinic_name')->default('Phòng khám An Tâm');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('logo')->nullable();
            $table->time('opening_time')->default('07:00:00');
            $table->time('closing_time')->default('20:00:00');
            $table->text('examination_policy')->nullable();
            $table->timestamps();
        });
    }

    public function down() { Schema::dropIfExists('clinic_settings'); }
}
