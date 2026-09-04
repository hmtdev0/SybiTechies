<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'title', 'slug', 'department', 'location', 'type', 'description', 'requirements',
    'display_order', 'status',
])]
class JobOpening extends Model
{
    use HasSlug, SoftDeletes;

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
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
