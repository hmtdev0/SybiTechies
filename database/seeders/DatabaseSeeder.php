<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * NOTE: intentionally does NOT use WithoutModelEvents — several models
     * (Service, Project, BlogPost, ...) rely on the HasSlug trait's
     * creating/updating model events to auto-generate slugs.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
            SiteSettingSeeder::class,
            EmailSettingSeeder::class,
            SeoMetaSeeder::class,
            HomeCmsSeeder::class,
            AboutCmsSeeder::class,
            ServiceSeeder::class,
            ProjectCategorySeeder::class,
            TechnologySeeder::class,
            ProjectSeeder::class,
            IndustrySeeder::class,
            ProcessStepSeeder::class,
            TestimonialSeeder::class,
            TeamMemberSeeder::class,
            FaqSeeder::class,
            BlogSeeder::class,
            JobOpeningSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
