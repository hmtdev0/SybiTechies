<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['home_hero_id', 'icon', 'number', 'suffix', 'label', 'display_order'])]
class HomeHeroStat extends Model
{
    public function hero(): BelongsTo
    {
        return $this->belongsTo(HomeHero::class, 'home_hero_id');
    }
}
