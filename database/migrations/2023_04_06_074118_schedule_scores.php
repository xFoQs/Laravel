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
        Schema::create('schedules_scores', function (Blueprint $table) {
            $table->id();
            $table->date('date_match');
            $table->integer('id_host_team');
            $table->integer('id_guest_team');
            $table->integer('host_scored');
            $table->integer('host_lost');
            $table->integer('quest_scored');
            $table->integer('quest_lost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
