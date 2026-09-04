<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        $techs = [
            ['icon' => 'bi-server', 'name' => 'Laravel', 'color' => '#FF2D20'],
            ['icon' => 'bi-filetype-php', 'name' => 'PHP', 'color' => '#777BB4'],
            ['icon' => 'bi-filetype-js', 'name' => 'JavaScript', 'color' => '#F7DF1E'],
            ['icon' => 'bi-filetype-html', 'name' => 'HTML5', 'color' => '#E34F26'],
            ['icon' => 'bi-filetype-css', 'name' => 'CSS3', 'color' => '#1572B6'],
            ['icon' => 'bi-bootstrap', 'name' => 'Bootstrap 5', 'color' => '#7952B3'],
            ['icon' => 'bi-phone', 'name' => 'Flutter', 'color' => '#02569B'],
            ['icon' => 'bi-filetype-py', 'name' => 'Python', 'color' => '#3776AB'],
            ['icon' => 'bi-database', 'name' => 'MySQL', 'color' => '#4479A1'],
            ['icon' => 'bi-git', 'name' => 'Git', 'color' => '#F05032'],
            ['icon' => 'bi-hdd-network', 'name' => 'REST APIs', 'color' => '#06B6D4'],
        ];

        foreach ($techs as $i => $tech) {
            Technology::query()->updateOrCreate(
                ['name' => $tech['name']],
                array_merge($tech, ['display_order' => $i + 1, 'status' => true])
            );
        }
    }
}
