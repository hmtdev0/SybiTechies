<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_hero_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_hero_id')->constrained()->cascadeOnDelete();
            $table->string('icon')->nullable();
            $table->unsignedInteger('number')->default(0);
            $table->string('suffix')->nullable();
            $table->string('label')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_hero_stats');
    }
};
