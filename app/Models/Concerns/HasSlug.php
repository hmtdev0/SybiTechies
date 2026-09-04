<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Auto-generates a unique slug from the model's `slug_source` attribute
 * (defaults to `title`/`name`) whenever the slug is left blank.
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug();
            }
        });

        static::updating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug();
            }
        });
    }

    protected function slugSourceValue(): string
    {
        return $this->title ?? $this->name ?? '';
    }

    protected function generateUniqueSlug(): string
    {
        $base = Str::slug($this->slugSourceValue());
        $slug = $base;
        $i = 1;

        $query = static::withoutGlobalScopes()->where('slug', $slug);
        if ($this->exists) {
            $query->where($this->getKeyName(), '!=', $this->getKey());
        }

        while ($query->exists()) {
            $slug = $base.'-'.$i++;
            $query = static::withoutGlobalScopes()->where('slug', $slug);
            if ($this->exists) {
                $query->where($this->getKeyName(), '!=', $this->getKey());
            }
        }

        return $slug;
    }
}
