<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['menu_id', 'parent_id', 'label', 'url', 'display_order', 'is_new_tab', 'status'])]
class MenuItem extends Model
{
    protected function casts(): array
    {
        return [
            'is_new_tab' => 'boolean',
            'status' => 'boolean',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('display_order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
