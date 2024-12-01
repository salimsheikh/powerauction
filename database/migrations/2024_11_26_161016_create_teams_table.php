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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('team_name', 100)->nullable();
            $table->string('team_logo', 250)->nullable();
            $table->string('team_logo_thumb', 250)->nullable();
            $table->string('virtual_point', 50)->nullable();
            $table->unsignedBigInteger('league_id')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('status',15)->default('publish');
            $table->unsignedBigInteger('created_by')->default(0); // Created by user
            $table->unsignedBigInteger('updated_by')->nullable(); // Updated by user
            $table->timestamps();
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('league_id')->references('id')->on('league')->onDelete('restrict');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
