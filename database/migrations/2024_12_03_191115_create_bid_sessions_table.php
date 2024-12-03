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
        Schema::create('bid_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('league_id')->nullable();
            $table->unsignedBigInteger('player_id');
            $table->timestamp('start_time')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('end_time')->nullable(); // Allow NULL if no value is provided
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->unsignedBigInteger('created_by')->default(0); // Created by user
            $table->unsignedBigInteger('updated_by')->nullable(); // Updated by user
            $table->timestamps();

            // Foreign keys (assuming related tables exist)
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
            $table->foreign('league_id')->references('id')->on('league')->onDelete('restrict');
            $table->foreign('player_id')->references('id')->on('players')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bid_sessions');
    }
};
