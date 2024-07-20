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
        Schema::create('shipping_points', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 20)->unique();
            $table->string('email', 40)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('address', 20);
            $table->string('phone_number', 15)->unique();
            $table->foreignId('admin_id')->constrained()->noActionOnDelete()->cascadeOnUpdate();
            $table->enum('status', ['w', 'f'])->default('w');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_points');
    }
};