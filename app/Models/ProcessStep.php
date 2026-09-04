<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['step_number', 'title', 'description', 'icon', 'display_order'])]
class ProcessStep extends Model
{
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
