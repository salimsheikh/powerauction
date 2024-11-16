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
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();// Primary key
                $table->string('category_name', 191)->unique();// Unique 10 digit code
                $table->decimal('base_price', 10, 2)->default(0); // Base price
                $table->longText('description')->nullable();
                $table->string('color_code', 10)->nullable();// Color code (e.g., #FFFFFF)
                $table->string('status',15)->default('publish');
                $table->unsignedBigInteger('created_by')->default(0); // Created by user
                $table->unsignedBigInteger('updated_by')->nullable(); // Updated by user
                $table->timestamps(); // Created at and updated at

                // Foreign keys
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
               
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
