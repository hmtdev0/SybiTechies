<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['photo', 'client_name', 'company', 'designation', 'review', 'rating', 'display_order', 'status'])]
class Testimonial extends Model
{
    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'rating' => 'integer',
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
}
