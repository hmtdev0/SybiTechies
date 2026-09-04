<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::query()->updateOrCreate(['id' => 1], [
            'company_name' => 'SysbiTechies',
            'email' => 'hello@sysbitechies.com',
            'phone' => '+1 (555) 123-4567',
            'whatsapp' => '+15551234567',
            'address' => '101 Innovation Drive, Tech Park, CA 94103',
            'business_hours' => 'Mon – Fri: 9:00 AM – 6:00 PM',
            'copyright_text' => '© '.date('Y').' SysbiTechies. All rights reserved.',
            'footer_text' => 'We are a premium software house transforming ideas into powerful digital solutions — building enterprise software, web & mobile apps, ERP and CRM systems that help businesses grow.',
            'facebook_url' => 'https://facebook.com/sysbitechies',
            'instagram_url' => 'https://instagram.com/sysbitechies',
            'linkedin_url' => 'https://linkedin.com/company/sysbitechies',
            'twitter_url' => 'https://x.com/sysbitechies',
            'github_url' => 'https://github.com/sysbitechies',
            'youtube_url' => null,
        ]);
    }
}
