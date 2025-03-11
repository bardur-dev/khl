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
        Schema::create('forwards', function (Blueprint $table) {
            $table->id();
            $table->string('middle_name');
            $table->foreignId('club_id')->constrained('clubs')->onDelete('cascade');
            $table->integer('goals_scored')->default(0);
            $table->integer('assists')->default(0);
            $table->integer('penalty_minutes')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forwards');
    }
};
