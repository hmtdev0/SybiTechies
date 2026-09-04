<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'blog_category_id', 'title', 'slug', 'excerpt', 'content', 'featured_image',
    'status', 'published_at', 'views_count', 'is_featured',
])]
class BlogPost extends Model
{
    use HasSeo, HasSlug, SoftDeletes;

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(BlogPostImage::class)->orderBy('display_order');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'blog_post_tag');
    }

    /** Same-category posts, excluding this one — used for "related posts". */
    public function relatedPosts(int $limit = 3)
    {
        return static::query()
            ->published()
            ->where('blog_category_id', $this->blog_category_id)
            ->where('id', '!=', $this->id)
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
