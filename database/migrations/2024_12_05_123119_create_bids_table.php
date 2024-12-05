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
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('team_id');
            $table->decimal('amount', 10, 2);
            $table->tinyInteger('is_winner')->default(0);
            $table->timestamp('bid_time')->useCurrent();
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('bid_sessions')->onDelete('restrict');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
