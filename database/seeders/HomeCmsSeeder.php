<?php

namespace Database\Seeders;

use App\Models\HomeHero;
use App\Models\Statistic;
use App\Models\TrustedCompany;
use App\Models\WhyChooseUsItem;
use Illuminate\Database\Seeder;

class HomeCmsSeeder extends Seeder
{
    public function run(): void
    {
        $hero = HomeHero::query()->updateOrCreate(['id' => 1], [
            'badge_text' => 'Trusted Software Development Company',
            'title' => 'Building Innovative Software That Helps Businesses Grow',
            'highlight_text' => 'Businesses Grow',
            'typed_words' => ['Web Applications.', 'Mobile Apps.', 'Desktop Software.', 'ERP Systems.', 'CRM Solutions.', 'AI Solutions.'],
            'description' => 'SysbiTechies is a full-service IT solutions company & software house. We design and engineer scalable web applications, mobile apps, desktop software, ERP and CRM systems, and AI-driven automation that move real businesses forward.',
            'btn1_text' => 'Get Started',
            'btn1_link' => '/contact',
            'btn2_text' => 'Our Projects',
            'btn2_link' => '/projects',
        ]);

        $heroStats = [
            ['icon' => 'bi-rocket-takeoff', 'number' => 150, 'suffix' => '+', 'label' => 'Projects Delivered', 'display_order' => 1],
            ['icon' => 'bi-emoji-smile', 'number' => 100, 'suffix' => '+', 'label' => 'Happy Clients', 'display_order' => 2],
            ['icon' => 'bi-award', 'number' => 6, 'suffix' => '+', 'label' => 'Years Experience', 'display_order' => 3],
        ];
        foreach ($heroStats as $stat) {
            $hero->stats()->updateOrCreate(['label' => $stat['label']], $stat);
        }

        $mainStats = [
            ['icon' => 'bi-check2-circle', 'number' => 150, 'suffix' => '+', 'label' => 'Projects Completed', 'display_order' => 1],
            ['icon' => 'bi-emoji-smile', 'number' => 100, 'suffix' => '+', 'label' => 'Happy Clients', 'display_order' => 2],
            ['icon' => 'bi-buildings', 'number' => 20, 'suffix' => '+', 'label' => 'Industries Served', 'display_order' => 3],
            ['icon' => 'bi-award', 'number' => 6, 'suffix' => '+', 'label' => 'Years Experience', 'display_order' => 4],
            ['icon' => 'bi-hand-thumbs-up', 'number' => 99, 'suffix' => '%', 'label' => 'Client Satisfaction', 'display_order' => 5],
            ['icon' => 'bi-headset', 'number' => 24, 'suffix' => '/7', 'label' => 'Technical Support', 'display_order' => 6],
        ];
        foreach ($mainStats as $stat) {
            Statistic::query()->updateOrCreate(['section' => 'home', 'label' => $stat['label']], array_merge($stat, ['section' => 'home']));
        }

        $companies = ['Nexora', 'Vertexa', 'Cloudify', 'Quantic', 'BrightPay', 'Medicore', 'EduSphere', 'Retailix', 'FinNova', 'Logiflow'];
        foreach ($companies as $i => $name) {
            TrustedCompany::query()->updateOrCreate(['name' => $name], ['display_order' => $i + 1]);
        }

        $whyUs = [
            ['icon' => 'bi-people-fill', 'title' => 'Experienced Team', 'description' => 'Senior engineers with deep expertise across the full technology stack.'],
            ['icon' => 'bi-briefcase-fill', 'title' => 'Business Focused Solutions', 'description' => 'We solve real problems and tie every feature back to business value.'],
            ['icon' => 'bi-diagram-2-fill', 'title' => 'Scalable Architecture', 'description' => 'Systems designed to grow effortlessly as your user base expands.'],
            ['icon' => 'bi-arrow-repeat', 'title' => 'Agile Development', 'description' => 'Iterative delivery with full transparency and frequent releases.'],
            ['icon' => 'bi-shield-lock-fill', 'title' => 'Secure Development', 'description' => 'Security-first practices baked into every stage of the build.'],
            ['icon' => 'bi-file-code-fill', 'title' => 'Clean Code', 'description' => 'Maintainable, well-tested and documented code you can build on.'],
            ['icon' => 'bi-clock-history', 'title' => 'On-Time Delivery', 'description' => 'Realistic milestones and dependable delivery, every single time.'],
            ['icon' => 'bi-life-preserver', 'title' => 'Long-Term Support', 'description' => 'A lasting partnership with proactive maintenance and support.'],
        ];
        foreach ($whyUs as $i => $item) {
            WhyChooseUsItem::query()->updateOrCreate(['title' => $item['title']], array_merge($item, ['display_order' => $i + 1]));
        }
    }
}
