<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'location'])]
class Menu extends Model
{
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('display_order');
    }

    /**
     * Same as items(), but excludes inactive entries — this is what
     * public-facing views (navbar/footer) should render, while the admin
     * Menu Manager keeps using items() so it can still manage hidden items.
     */
    public function activeItems(): HasMany
    {
        return $this->items()->active();
    }

    public static function forLocation(string $location): ?self
    {
        return static::query()->where('location', $location)->first();
    }
}
