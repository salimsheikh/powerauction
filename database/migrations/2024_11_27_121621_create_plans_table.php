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
            $table->string('slug',10)->unique();
            $table->string('name',10)->unique();
            $table->integer('amount',10)->default(0);
            $table->integer('order',5)->default(0);
            $table->string('status',15)->default('publish');
            $table->unsignedBigInteger('created_by')->default(0); // Created by user
            $table->unsignedBigInteger('updated_by')->nullable(); // Updated by user
            $table->timestamps();
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
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
