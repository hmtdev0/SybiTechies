<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['icon' => 'bi-code-square', 'title' => 'Custom Software Development', 'short_description' => 'Tailor-made software engineered precisely around your unique business workflows.'],
            ['icon' => 'bi-globe2', 'title' => 'Web Development', 'short_description' => 'Fast, secure and scalable websites and web apps built on modern frameworks.'],
            ['icon' => 'bi-phone', 'title' => 'Mobile App Development', 'short_description' => 'Native-quality iOS & Android apps with smooth UX and reliable performance.'],
            ['icon' => 'bi-window-desktop', 'title' => 'Desktop Application Development', 'short_description' => 'Powerful cross-platform desktop software for Windows, macOS and Linux.'],
            ['icon' => 'bi-buildings', 'title' => 'Enterprise Software Solutions', 'short_description' => 'Robust systems that scale with large teams, high traffic and complex data.'],
            ['icon' => 'bi-diagram-3', 'title' => 'ERP Development', 'short_description' => 'Unify operations, inventory, finance and HR into one intelligent platform.'],
            ['icon' => 'bi-people', 'title' => 'CRM Development', 'short_description' => 'Manage leads, sales pipelines and customers with automation that converts.'],
            ['icon' => 'bi-robot', 'title' => 'AI & Business Automation', 'short_description' => 'Automate repetitive work and unlock insights with smart AI-driven tools.'],
            ['icon' => 'bi-cart3', 'title' => 'E-Commerce Solutions', 'short_description' => 'High-converting online stores with secure payments and inventory control.'],
            ['icon' => 'bi-palette', 'title' => 'UI/UX Design', 'short_description' => 'Beautiful, intuitive interfaces designed to delight users and boost engagement.'],
            ['icon' => 'bi-wordpress', 'title' => 'WordPress Development', 'short_description' => 'Custom themes, plugins and blazing-fast managed WordPress websites.'],
            ['icon' => 'bi-tools', 'title' => 'Maintenance & Support', 'short_description' => 'Proactive monitoring, updates and 24/7 support to keep you running.'],
        ];

        foreach ($services as $i => $service) {
            Service::query()->updateOrCreate(
                ['title' => $service['title']],
                array_merge($service, [
                    'full_description' => $service['short_description'],
                    'display_order' => $i + 1,
                    'status' => true,
                    'is_featured' => false,
                ])
            );
        }
    }
}
