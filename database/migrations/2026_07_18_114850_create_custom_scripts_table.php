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
        Schema::create('custom_scripts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('placement', ['head', 'footer'])->default('head');
            $table->longText('code');
            $table->boolean('status')->default(true);
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_scripts');
    }
};
