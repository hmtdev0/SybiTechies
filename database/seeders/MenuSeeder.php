<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $headerMenu = Menu::query()->updateOrCreate(['location' => 'header'], ['name' => 'Main Navigation']);
        $headerItems = [
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'About', 'url' => '/about'],
            ['label' => 'Services', 'url' => '/services'],
            ['label' => 'Projects', 'url' => '/projects'],
            ['label' => 'Careers', 'url' => '/careers'],
            ['label' => 'Process', 'url' => '/#process'],
            ['label' => 'Why Us', 'url' => '/#why-us'],
            ['label' => 'Testimonials', 'url' => '/#testimonials'],
            ['label' => 'FAQ', 'url' => '/faq'],
            ['label' => 'Contact', 'url' => '/contact'],
        ];
        foreach ($headerItems as $i => $item) {
            $headerMenu->items()->updateOrCreate(
                ['label' => $item['label']],
                array_merge($item, ['display_order' => $i + 1, 'status' => true])
            );
        }

        $footerMenu = Menu::query()->updateOrCreate(['location' => 'footer'], ['name' => 'Footer Quick Links']);
        $footerItems = [
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'About Us', 'url' => '/about'],
            ['label' => 'Services', 'url' => '/services'],
            ['label' => 'Projects', 'url' => '/projects'],
            ['label' => 'Careers', 'url' => '/careers'],
            ['label' => 'FAQ', 'url' => '/faq'],
            ['label' => 'Why Choose Us', 'url' => '/#why-us'],
            ['label' => 'Contact', 'url' => '/contact'],
        ];
        foreach ($footerItems as $i => $item) {
            $footerMenu->items()->updateOrCreate(
                ['label' => $item['label']],
                array_merge($item, ['display_order' => $i + 1, 'status' => true])
            );
        }
    }
}
