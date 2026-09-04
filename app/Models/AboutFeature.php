<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['icon', 'title', 'description', 'display_order'])]
class AboutFeature extends Model
{
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
