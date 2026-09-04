<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['service_id', 'feature_text', 'display_order'])]
class ServiceFeature extends Model
{
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
