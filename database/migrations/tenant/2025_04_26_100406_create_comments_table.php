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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('issue_id');
            $table->foreign('issue_id')->references('id')->on('issues')->onDelete('cascade');
            $table->unsignedBigInteger('comment_by');
            $table->foreign('comment_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('update_by')->default(0)->nullable();
           // $table->foreign('update_by')->references('id')->on('users')->onDelete('cascade');
            $table->text('description');
            $table->string('attachment', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
