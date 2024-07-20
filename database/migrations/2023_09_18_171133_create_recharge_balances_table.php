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
        Schema::create('recharge_balances', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name', 20);
            $table->integer('balance');
            $table->foreignId('user_id')->constrained()->noActionOnDelete()->cascadeOnUpdate();
            $table->foreignId('shipping_point_id')->constrained()->noActionOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recharge_balances');
    }
};