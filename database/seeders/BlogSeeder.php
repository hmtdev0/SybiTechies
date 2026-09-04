<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Web Development', 'Mobile Development', 'Business Software', 'Company News'];
        foreach ($categories as $i => $name) {
            BlogCategory::query()->updateOrCreate(['name' => $name], ['display_order' => $i + 1, 'status' => true]);
        }

        $posts = [
            [
                'title' => 'The Five Devices You Need To Work Anytime And Anywhere',
                'category' => 'Business Software',
                'excerpt' => 'A practical look at the hardware setup that keeps distributed engineering teams productive.',
                'content' => "Remote-first teams need more than a laptop. In this post we cover the five devices our own engineers rely on to stay productive from anywhere — from a reliable second monitor to a mechanical keyboard that survives long sprint sessions.\n\nInvesting in the right hardware pays for itself quickly in fewer errors and faster delivery.",
            ],
            [
                'title' => 'TikTok Illegally Collecting Data, Sharing With China',
                'category' => 'Company News',
                'excerpt' => 'What the latest data-privacy headlines mean for software teams building consumer apps.',
                'content' => "Data privacy regulations are tightening worldwide. For teams building consumer-facing apps, this means baking consent management and data minimization into the architecture from day one, not bolting it on after launch.\n\nWe walk through the practices SysbiTechies applies on every client project to stay ahead of compliance requirements.",
            ],
            [
                'title' => 'Where And How To Watch Live Stream Today',
                'category' => 'Web Development',
                'excerpt' => 'A quick technical overview of building reliable live-streaming experiences on the web.',
                'content' => "Live streaming on the web comes with unique challenges: latency, adaptive bitrate, and cross-browser playback support. This post breaks down the architecture patterns we use when a client needs real-time video delivered reliably at scale.",
            ],
            [
                'title' => 'Why Every Growing Business Needs a Custom CRM',
                'category' => 'Business Software',
                'excerpt' => 'Off-the-shelf CRMs are a great start — until they start holding your sales team back.',
                'content' => "As businesses scale past their first few hundred customers, generic CRM tools often become a bottleneck. We share the signs it's time to invest in a custom CRM, and how a tailored data model can unlock real sales velocity.",
            ],
        ];

        foreach ($posts as $i => $post) {
            $category = BlogCategory::query()->where('name', $post['category'])->first();

            BlogPost::query()->updateOrCreate(
                ['title' => $post['title']],
                [
                    'blog_category_id' => $category?->id,
                    'excerpt' => $post['excerpt'],
                    'content' => $post['content'],
                    'status' => 'published',
                    'published_at' => now()->subDays(($i + 1) * 7),
                    'is_featured' => $i === 0,
                ]
            );
        }
    }
}
