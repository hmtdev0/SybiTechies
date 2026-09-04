<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            ['icon' => 'bi-heart-pulse', 'name' => 'Healthcare'],
            ['icon' => 'bi-mortarboard', 'name' => 'Education'],
            ['icon' => 'bi-bag', 'name' => 'Retail'],
            ['icon' => 'bi-cup-hot', 'name' => 'Restaurants'],
            ['icon' => 'bi-cash-coin', 'name' => 'Finance'],
            ['icon' => 'bi-house-door', 'name' => 'Real Estate'],
            ['icon' => 'bi-gear-wide-connected', 'name' => 'Manufacturing'],
            ['icon' => 'bi-truck', 'name' => 'Logistics'],
            ['icon' => 'bi-building', 'name' => 'Corporate'],
            ['icon' => 'bi-lightning-charge', 'name' => 'Startups'],
        ];

        foreach ($industries as $i => $industry) {
            Industry::query()->updateOrCreate(
                ['name' => $industry['name']],
                array_merge($industry, ['display_order' => $i + 1, 'status' => true])
            );
        }
    }
}
