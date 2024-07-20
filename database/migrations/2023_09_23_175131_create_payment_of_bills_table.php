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
        Schema::create('payment_of_bills', function (Blueprint $table) {
            $table->id();
            $table->integer('balance');
            $table->string('details');
            $table->foreignId('user_id')->constrained()->noActionOnDelete()->cascadeOnUpdate();
            $table->foreignId('payment_provider_id')->constrained()->noActionOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_of_bills');
    }
};