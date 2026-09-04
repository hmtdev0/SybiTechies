<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'icon', 'image', 'title', 'slug', 'short_description', 'full_description',
    'display_order', 'status', 'is_featured',
])]
class Service extends Model
{
    use HasSeo, HasSlug, SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function features(): HasMany
    {
        return $this->hasMany(ServiceFeature::class)->orderBy('display_order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
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
