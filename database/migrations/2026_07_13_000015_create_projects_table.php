<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->string('client')->nullable();
            $table->string('duration')->nullable();
            $table->longText('description')->nullable();
            $table->longText('challenges')->nullable();
            $table->longText('solutions')->nullable();
            $table->string('live_url')->nullable();
            $table->string('github_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
