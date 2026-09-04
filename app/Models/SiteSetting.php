<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'company_name', 'logo', 'favicon', 'email', 'phone', 'whatsapp', 'address',
    'google_maps_embed', 'business_hours', 'copyright_text', 'footer_text',
    'facebook_url', 'instagram_url', 'linkedin_url', 'twitter_url', 'github_url', 'youtube_url',
])]
class SiteSetting extends Model
{
    /**
     * This table only ever holds a single row. Always use this accessor
     * instead of querying the model directly, so callers never have to
     * worry about the row not existing yet.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate(['id' => 1]);
    }

    /**
     * The brand logo/name is rendered in two tones (e.g. "Sysbi" + "Techies").
     * Splits on the first space when present; otherwise splits a single
     * CamelCase word (e.g. "SysbiTechies") at its second capital letter.
     * Falls back to the whole name with an empty accent rather than ever
     * duplicating the string.
     */
    public function brandFirstPart(): string
    {
        $name = trim($this->company_name ?: 'SysbiTechies');

        if (str_contains($name, ' ')) {
            return Str::before($name, ' ');
        }

        if (preg_match('/^([A-Z][a-z]*)([A-Z].*)$/', $name, $matches)) {
            return $matches[1];
        }

        return $name;
    }

    public function brandAccentPart(): string
    {
        $name = trim($this->company_name ?: 'SysbiTechies');

        if (str_contains($name, ' ')) {
            return Str::after($name, ' ');
        }

        if (preg_match('/^([A-Z][a-z]*)([A-Z].*)$/', $name, $matches)) {
            return $matches[2];
        }

        return '';
    }
}
