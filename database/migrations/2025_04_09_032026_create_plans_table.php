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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->string('subtitle', 50);
            $table->integer('price')->default(0)->nullable();
            $table->string('currency', 10);
            $table->integer('no_of_user')->default(0);
            $table->integer('no_of_project')->default(0);
            $table->integer('whats_up_intigration')->default(0);
            $table->integer('sms_intigration')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
