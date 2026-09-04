<?php

namespace Database\Seeders;

use App\Models\SeoMeta;
use Illuminate\Database\Seeder;

class SeoMetaSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'home' => [
                'title' => 'SysbiTechies — Transforming Ideas Into Powerful Digital Solutions',
                'meta_description' => 'SysbiTechies is a premium IT solutions company & software house building web applications, mobile apps, desktop software, ERP, CRM and AI solutions for businesses worldwide.',
                'meta_keywords' => 'software house, IT solutions, web development, mobile app development, ERP, CRM, custom software, Laravel development, AI solutions',
                'og_title' => 'SysbiTechies — IT Solutions & Software House',
                'og_description' => 'Transforming Ideas Into Powerful Digital Solutions.',
            ],
            'services' => [
                'title' => 'Our Services — SysbiTechies',
                'meta_description' => 'Explore the full range of software development services offered by SysbiTechies — web, mobile, desktop, ERP, CRM and AI solutions.',
                'og_title' => 'Our Services — SysbiTechies',
                'og_description' => 'Full-service software development for growing businesses.',
            ],
            'projects' => [
                'title' => 'Our Projects — SysbiTechies',
                'meta_description' => 'Browse the portfolio of software products SysbiTechies has designed and shipped for clients across industries.',
                'og_title' => 'Our Projects — SysbiTechies',
                'og_description' => 'A showcase of software we have built for our clients.',
            ],
            'blog' => [
                'title' => 'Blog — SysbiTechies',
                'meta_description' => 'Insights, tutorials and updates from the SysbiTechies engineering team.',
                'og_title' => 'Blog — SysbiTechies',
                'og_description' => 'Insights from the SysbiTechies team.',
            ],
        ];

        foreach ($pages as $key => $data) {
            SeoMeta::query()->updateOrCreate(['page_key' => $key], $data);
        }
    }
}
