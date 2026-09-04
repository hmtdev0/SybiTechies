<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('badge_text')->nullable();
            $table->string('title')->nullable();
            $table->string('highlight_text')->nullable();
            $table->json('typed_words')->nullable();
            $table->text('description')->nullable();
            $table->string('btn1_text')->nullable();
            $table->string('btn1_link')->nullable();
            $table->string('btn2_text')->nullable();
            $table->string('btn2_link')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_heroes');
    }
};
