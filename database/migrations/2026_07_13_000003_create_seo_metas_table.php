<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_metas', function (Blueprint $table) {
            $table->id();
            // Either a polymorphic link to a model (Service/Project/BlogPost) ...
            $table->nullableMorphs('seo_able');
            // ... or a static page key (home, services, projects, blog) when there's no model.
            $table->string('page_key')->nullable()->unique();
            $table->string('title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 500)->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description', 500)->nullable();
            $table->text('schema_json')->nullable();
            $table->string('robots')->default('index, follow');
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_metas');
    }
};
