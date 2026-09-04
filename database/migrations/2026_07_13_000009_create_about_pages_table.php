<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('mission_title')->nullable();
            $table->text('mission_text')->nullable();
            $table->string('vision_title')->nullable();
            $table->text('vision_text')->nullable();
            $table->string('story_title')->nullable();
            $table->text('story_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_pages');
    }
};
