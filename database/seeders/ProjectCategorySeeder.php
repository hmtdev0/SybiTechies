<?php

namespace Database\Seeders;

use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;

class ProjectCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Web', 'Mobile', 'Desktop', 'ERP', 'CRM'];

        foreach ($categories as $i => $name) {
            ProjectCategory::query()->updateOrCreate(
                ['name' => $name],
                ['display_order' => $i + 1, 'status' => true]
            );
        }
    }
}
