<?php

namespace App\Models\Concerns;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Gives a model a polymorphic SEO meta record (title, description, OG tags, schema, etc.)
 * via the shared `seo_metas` table.
 */
trait HasSeo
{
    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seo_able');
    }
}
