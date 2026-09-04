<?php

namespace App\Services;

use App\Models\EmailSetting;

/**
 * Applies the admin-configured SMTP settings to Laravel's mail config at
 * send time, so outgoing mail uses whatever is saved in Email Settings
 * instead of the static .env values.
 *
 * Note: this only works when config isn't cached (no `config:cache` in
 * production) — an accepted tradeoff for admin-editable SMTP settings.
 */
class MailConfigurator
{
    public static function apply(EmailSetting $settings): void
    {
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $settings->smtp_host,
            'mail.mailers.smtp.port' => $settings->smtp_port,
            'mail.mailers.smtp.username' => $settings->smtp_username,
            'mail.mailers.smtp.password' => $settings->smtp_password,
            'mail.mailers.smtp.encryption' => $settings->smtp_encryption ?: null,
            'mail.from.address' => $settings->from_address ?: config('mail.from.address'),
            'mail.from.name' => $settings->from_name ?: config('mail.from.name'),
        ]);
    }
}
