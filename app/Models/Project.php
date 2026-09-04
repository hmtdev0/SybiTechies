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
    'project_category_id', 'name', 'slug', 'thumbnail', 'client', 'duration',
    'description', 'challenges', 'solutions', 'live_url', 'github_url',
    'is_featured', 'display_order', 'status',
])]
class Project extends Model
{
    use HasSeo, HasSlug, SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected function slugSourceValue(): string
    {
        return $this->name ?? '';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('display_order');
    }

    public function features(): HasMany
    {
        return $this->hasMany(ProjectFeature::class)->orderBy('display_order');
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class, 'project_technology');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
