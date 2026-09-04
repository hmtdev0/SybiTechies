<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'seo_able_type', 'seo_able_id', 'page_key', 'title', 'meta_description', 'meta_keywords',
    'canonical_url', 'og_image', 'og_title', 'og_description', 'schema_json', 'robots', 'slug',
])]
class SeoMeta extends Model
{
    /** Static pages manageable in the SEO Manager that aren't backed by an Eloquent model. */
    public const PAGE_KEYS = ['home', 'about', 'contact', 'services', 'projects', 'blog', 'careers', 'faq', 'privacy-policy', 'terms-conditions'];

    public function seoAble(): MorphTo
    {
        return $this->morphTo();
    }

    public static function forPageKey(string $key): self
    {
        return static::query()->firstOrCreate(['page_key' => $key]);
    }
}
