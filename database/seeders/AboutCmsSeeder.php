<?php

namespace Database\Seeders;

use App\Models\AboutFeature;
use App\Models\AboutPage;
use App\Models\AboutTimeline;
use App\Models\Statistic;
use Illuminate\Database\Seeder;

class AboutCmsSeeder extends Seeder
{
    public function run(): void
    {
        AboutPage::query()->updateOrCreate(['id' => 1], [
            'image' => 'assets/images/about/team-office.jpg',
            'mission_title' => 'Our Mission',
            'mission_text' => 'To empower businesses with reliable, future-ready software that drives measurable growth.',
            'vision_title' => 'Our Vision',
            'vision_text' => 'To be a globally trusted engineering partner known for quality, integrity and innovation.',
            'story_title' => 'Our Story',
            'story_text' => 'SysbiTechies started with a simple goal: help businesses turn ambitious ideas into software that actually ships. Today we partner with startups and enterprises alike, engineering web, mobile, desktop, ERP and CRM solutions across a wide range of industries.',
        ]);

        AboutFeature::query()->updateOrCreate(['title' => 'Core Values'], [
            'icon' => 'bi-gem',
            'description' => 'Transparency, craftsmanship, ownership and a relentless focus on client success.',
            'display_order' => 1,
        ]);

        $aboutStats = [
            ['label' => 'Projects', 'number' => 150, 'suffix' => '+', 'icon' => 'bi-check2-circle', 'display_order' => 1],
            ['label' => 'Clients', 'number' => 100, 'suffix' => '+', 'icon' => 'bi-emoji-smile', 'display_order' => 2],
            ['label' => 'Countries', 'number' => 20, 'suffix' => '+', 'icon' => 'bi-globe', 'display_order' => 3],
            ['label' => 'Years', 'number' => 6, 'suffix' => '+', 'icon' => 'bi-award', 'display_order' => 4],
        ];
        foreach ($aboutStats as $stat) {
            Statistic::query()->updateOrCreate(['section' => 'about', 'label' => $stat['label']], array_merge($stat, ['section' => 'about']));
        }

        // Placeholder milestones — editable any time from Admin > About Page > Timeline.
        $timeline = [
            ['year' => '2020', 'title' => 'SysbiTechies Founded', 'description' => 'Started as a small team of engineers with one goal: build software that actually ships.'],
            ['year' => '2021', 'title' => 'First Enterprise Client', 'description' => 'Landed our first enterprise-scale engagement and delivered a production system on schedule.'],
            ['year' => '2022', 'title' => 'Expanded Into Mobile & ERP', 'description' => 'Grew the team and broadened our stack to cover mobile apps and full ERP systems.'],
            ['year' => '2024', 'title' => '100th Client Milestone', 'description' => 'Crossed 100 businesses served across a dozen industries worldwide.'],
            ['year' => 'Today', 'title' => 'Still Growing', 'description' => 'A senior, focused team continuing to ship reliable software for ambitious businesses.'],
        ];
        foreach ($timeline as $i => $item) {
            AboutTimeline::query()->updateOrCreate(['title' => $item['title']], array_merge($item, ['display_order' => $i + 1]));
        }
    }
}
