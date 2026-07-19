<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_code', 20)->nullable()->unique();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('appointment_id')->unique()->constrained()->restrictOnDelete();
            $table->foreignId('examination_ticket_id')->unique()->constrained()->restrictOnDelete();
            $table->foreignId('medical_record_id')->unique()->constrained()->restrictOnDelete();
            $table->decimal('service_amount', 12, 2)->default(0);
            $table->decimal('medicine_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->string('payment_status', 20)->default('unpaid')->index();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('stock_deducted_at')->nullable();
            $table->timestamp('stock_restored_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down() { Schema::dropIfExists('invoices'); }
}
