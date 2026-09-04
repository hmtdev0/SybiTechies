<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
