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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->string('name', 50);
            $table->string('email', 50);
            $table->string('domain', 50);
            $table->string('phone', 50);
            $table->string('password', 50);
            $table->string('gender', 50);
            $table->string('credit_card', 50)->nullable();
            $table->string('expiry', 50)->nullable();
            $table->integer('approved_by')->default(0)->nullable();
            
            $table->integer('payment_status')->default(0)->nullable(); // 0 panding, 1 success 2 fail
            $table->integer('status')->default(0)->nullable(); // 0 panding, 1 approved 2 recjected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
