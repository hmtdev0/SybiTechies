<?php

namespace Database\Seeders;

use App\Models\JobOpening;
use Illuminate\Database\Seeder;

class JobOpeningSeeder extends Seeder
{
    public function run(): void
    {
        // Placeholder openings — editable any time from Admin > Careers > Job Openings.
        $jobs = [
            [
                'title' => 'Senior Laravel Developer',
                'department' => 'Engineering',
                'location' => 'Remote',
                'type' => 'Full-time',
                'description' => '<p>We\'re looking for a senior Laravel developer to help design and build client projects across web, ERP and CRM systems. You\'ll work closely with our design and QA team from discovery through launch.</p>',
                'requirements' => '<ul><li>4+ years of professional PHP/Laravel experience</li><li>Solid understanding of relational database design</li><li>Comfortable working directly with clients on requirements</li><li>Experience with REST APIs and modern front-end tooling</li></ul>',
            ],
            [
                'title' => 'UI/UX Designer',
                'department' => 'Design',
                'location' => 'Remote',
                'type' => 'Full-time',
                'description' => '<p>Design premium, conversion-focused interfaces for web and mobile products across a range of industries, working alongside our engineering team from wireframe to shipped feature.</p>',
                'requirements' => '<ul><li>A strong portfolio of shipped product design work</li><li>Proficiency in Figma</li><li>Comfortable presenting and defending design decisions</li><li>Basic understanding of front-end implementation constraints</li></ul>',
            ],
            [
                'title' => 'Mobile App Developer (Flutter)',
                'department' => 'Engineering',
                'location' => 'Hybrid',
                'type' => 'Full-time',
                'description' => '<p>Build and maintain cross-platform mobile applications for our clients, from early prototypes through App Store / Play Store launch and ongoing support.</p>',
                'requirements' => '<ul><li>2+ years building production Flutter apps</li><li>Experience integrating REST APIs and third-party SDKs</li><li>Familiarity with native iOS/Android build and release processes</li></ul>',
            ],
        ];

        foreach ($jobs as $i => $job) {
            JobOpening::query()->updateOrCreate(
                ['title' => $job['title']],
                array_merge($job, ['display_order' => $i + 1, 'status' => true])
            );
        }
    }
}
