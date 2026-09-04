<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'image', 'mission_title', 'mission_text', 'vision_title', 'vision_text',
    'story_title', 'story_text',
])]
class AboutPage extends Model
{
    public static function current(): self
    {
        return static::query()->firstOrCreate(['id' => 1]);
    }
}
