<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['email', 'status', 'subscribed_at'])]
class NewsletterSubscriber extends Model
{
    protected function casts(): array
    {
        return ['subscribed_at' => 'datetime'];
    }

    public function scopeSubscribed($query)
    {
        return $query->where('status', 'subscribed');
    }
}
