<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'display_order', 'status'])]
class BlogCategory extends Model
{
    use HasSlug;

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
