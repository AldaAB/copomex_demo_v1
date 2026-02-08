<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('copomex_settings', function (Blueprint $table) {
            $table->id();
            $table->string('token_test')->default('pruebas');
            $table->text('token_real')->nullable();
            $table->unsignedInteger('credits_real')->nullable();
            $table->timestamp('credits_checked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('copomex_settings');
    }
};
