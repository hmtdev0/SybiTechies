<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'badge_text', 'title', 'highlight_text', 'typed_words', 'description',
    'btn1_text', 'btn1_link', 'btn2_text', 'btn2_link', 'image',
])]
class HomeHero extends Model
{
    protected function casts(): array
    {
        return [
            'typed_words' => 'array',
        ];
    }

    public function stats(): HasMany
    {
        return $this->hasMany(HomeHeroStat::class)->orderBy('display_order');
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate(['id' => 1]);
    }
}
