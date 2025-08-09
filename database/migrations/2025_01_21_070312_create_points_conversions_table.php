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
        Schema::create('points_conversions', function (Blueprint $table) {
            $table->id();
            $table->integer('points');
            $table->decimal('value', 8, 2);
            $table->integer('max_percentage');
            $table->integer('vat');
            $table->integer('vat_negation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points_conversions');
    }
};
