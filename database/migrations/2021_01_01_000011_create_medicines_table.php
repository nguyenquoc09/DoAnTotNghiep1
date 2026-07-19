<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicinesTable extends Migration
{
    public function up()
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('medicine_code', 20)->unique();
            $table->string('name', 150)->index();
            $table->string('active_ingredient')->nullable();
            $table->string('dosage_form', 100)->nullable();
            $table->string('strength', 100)->nullable();
            $table->string('unit', 30);
            $table->string('manufacturer')->nullable();
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2);
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->unsignedInteger('minimum_stock')->default(0);
            $table->text('usage_instruction')->nullable();
            $table->text('contraindication')->nullable();
            $table->string('status', 20)->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() { Schema::dropIfExists('medicines'); }
}
