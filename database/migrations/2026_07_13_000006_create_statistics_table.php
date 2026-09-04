<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->enum('section', ['hero', 'home', 'about'])->default('home');
            $table->string('icon')->nullable();
            $table->unsignedInteger('number')->default(0);
            $table->string('suffix')->nullable();
            $table->string('label')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
