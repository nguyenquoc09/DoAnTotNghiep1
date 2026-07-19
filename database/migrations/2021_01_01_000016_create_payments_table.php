<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_code', 20)->nullable()->unique();
            $table->foreignId('invoice_id')->constrained()->restrictOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('payment_method', 30);
            $table->string('transaction_reference')->nullable();
            $table->timestamp('payment_date');
            $table->foreignId('received_by')->constrained('users')->restrictOnDelete();
            $table->string('status', 20)->default('completed')->index();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down() { Schema::dropIfExists('payments'); }
}
