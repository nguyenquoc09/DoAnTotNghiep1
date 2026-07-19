<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->restrictOnDelete();
            $table->string('name', 150);
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable()->index();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('status', 20)->default('active')->index();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() { Schema::dropIfExists('users'); }
}
