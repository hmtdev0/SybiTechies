<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'placement', 'code', 'status', 'display_order'])]
class CustomScript extends Model
{
    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    public function scopeForPlacement($query, string $placement)
    {
        return $query->where('placement', $placement);
    }
}
