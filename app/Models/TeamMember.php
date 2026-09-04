<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'photo', 'name', 'position', 'bio', 'facebook_url', 'twitter_url',
    'linkedin_url', 'instagram_url', 'github_url', 'display_order', 'status',
])]
class TeamMember extends Model
{
    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function initials(): string
    {
        $parts = collect(preg_split('/\s+/', trim($this->name)))->filter();
        $initials = $parts->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)));

        return $initials->first().($initials->count() > 1 ? $initials->last() : '');
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
