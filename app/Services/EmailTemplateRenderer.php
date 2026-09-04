<?php

namespace App\Services;

/**
 * Substitutes {{placeholder}} tokens in admin-edited email templates.
 * Shared by the actual outgoing Mailable and the admin live preview so
 * both always render identically.
 */
class EmailTemplateRenderer
{
    /**
     * $escape must stay true for HTML body substitution (the values can
     * come from public form input) and false for plain-text contexts like
     * an email subject line, where HTML-escaping would corrupt characters
     * such as "&".
     */
    public static function render(?string $template, array $placeholders, bool $escape = true): string
    {
        $template = $template ?? '';

        foreach ($placeholders as $key => $value) {
            $value = $escape ? e((string) $value) : (string) $value;
            $template = str_replace('{{'.$key.'}}', $value, $template);
        }

        return $template;
    }

    /**
     * Quill outputs bare <img> tags with no spacing, and most email clients
     * ignore external/head <style> blocks — so images need their spacing
     * inlined directly onto the tag to render correctly in Gmail/Outlook/etc.
     * Leaves any <img> that already has an explicit style attribute alone.
     */
    public static function styleImages(string $html): string
    {
        return preg_replace_callback('/<img\b([^>]*)>/i', function (array $matches) {
            if (stripos($matches[1], 'style=') !== false) {
                return $matches[0];
            }

            return '<img'.$matches[1].' style="max-width:100%;display:block;margin:16px 0;border-radius:8px;">';
        }, $html) ?? $html;
    }

    /**
     * Zeroes out the default browser/email-client <p> margin (which varies
     * client to client — Outlook, Gmail and Apple Mail all differ) so
     * paragraph spacing in a template is deliberately set, not inherited.
     * Leaves any <p> that already has an explicit style attribute alone.
     */
    public static function styleParagraphs(string $html): string
    {
        return preg_replace_callback('/<p\b([^>]*)>/i', function (array $matches) {
            if (stripos($matches[1], 'style=') !== false) {
                return $matches[0];
            }

            return '<p'.$matches[1].' style="margin:0;">';
        }, $html) ?? $html;
    }

    /**
     * Applies every inline-styling pass needed to make admin-edited email
     * HTML render consistently across clients.
     */
    public static function styleContent(string $html): string
    {
        return static::styleParagraphs(static::styleImages($html));
    }
}
