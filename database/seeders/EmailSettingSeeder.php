<?php

namespace Database\Seeders;

use App\Models\EmailSetting;
use Illuminate\Database\Seeder;

class EmailSettingSeeder extends Seeder
{
    public function run(): void
    {
        // email_enabled defaults to false — the admin must add real SMTP
        // credentials and switch it on from Admin > Email Settings.
        EmailSetting::query()->updateOrCreate(['id' => 1], [
            'smtp_encryption' => 'tls',
            'email_enabled' => false,
            'autoreply_enabled' => true,
            'autoreply_subject' => 'Thank You for Contacting {{company_name}}!',
            'autoreply_body' => "<p>Hi {{name}},</p>"
                ."<p>Thank you for reaching out to {{company_name}}! We've received your message and a member of our team will get back to you within one business day.</p>"
                .'<p>In the meantime, feel free to explore our website to learn more about what we do.</p>'
                .'<p>Best regards,<br>The {{company_name}} Team</p>',
        ]);
    }
}
