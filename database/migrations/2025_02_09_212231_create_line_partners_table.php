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
        Schema::create('line_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forward_id')->constrained('forwards')->onDelete('cascade');
            $table->foreignId('partner_id')->constrained('forwards')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_partners');
    }
};
