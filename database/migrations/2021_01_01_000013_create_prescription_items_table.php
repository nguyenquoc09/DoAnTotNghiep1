<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionItemsTable extends Migration
{
    public function up()
    {
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->string('dosage', 100);
            $table->string('frequency', 100)->nullable();
            $table->string('duration', 100)->nullable();
            $table->string('usage_time', 100)->nullable();
            $table->text('instruction')->nullable();
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
            $table->unique(['prescription_id', 'medicine_id']);
        });
    }

    public function down() { Schema::dropIfExists('prescription_items'); }
}
