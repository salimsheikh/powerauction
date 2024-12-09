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
        Schema::create('league', function (Blueprint $table) {
            $table->id();           
            $table->string('league_name', 100)->nullable();
            $table->string('description', 250)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('auction_view')->default(0);
            $table->string('category', 100)->nullable();
            $table->tinyInteger('unsold')->default(0);

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
        Schema::dropIfExists('league');
    }
};
