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
        Schema::create('players', function (Blueprint $table) {
            $table->id('id');
            $table->string('uniq_id', 100)->nullable();
            $table->string('player_name', 100)->nullable();
            $table->string('nickname', 100)->nullable();
            $table->string('mobile', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('category_id', 10)->nullable();
            $table->date('dob')->nullable();
            $table->string('image', 250)->nullable();
            $table->string('image_thumb', 250)->nullable();
            $table->string('bat_type', 20)->nullable();
            $table->string('ball_type', 50)->nullable();
            $table->string('type', 200)->nullable();
            $table->string('profile_type', 20)->nullable();
            $table->string('style', 100)->nullable();
            $table->string('last_played_league', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('status',15)->default('publish');
           
            $table->integer('order_id')->default(9999);
            $table->primary('id');

            $table->unsignedBigInteger('created_by')->default(0); // Created by user
            $table->unsignedBigInteger('updated_by')->nullable(); // Updated by user

            $table->timestamps(); // Created at and updated at

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict'); // Restrict deletion if category is used
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
