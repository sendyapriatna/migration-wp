<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sshaccess', function (Blueprint $table) {
            $table->id();
            $table->string('source_host');
            $table->string('source_username');
            $table->string('source_password');
            $table->string('source_path');
            $table->integer('source_port');
            $table->string('target_host');
            $table->string('target_username');
            $table->string('target_password');
            $table->string('target_path');
            $table->integer('target_port');
            $table->enum('status', ['OK','ERR'])->default('OK');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sshaccess');
    }
};
