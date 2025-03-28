<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weather_menu_preferences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('weather_condition');
            $table->uuid('menu_id');
            $table->integer('preference_score');
            $table->text('recommendation_reason')->nullable();
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_menu_preferences');
    }
}; 