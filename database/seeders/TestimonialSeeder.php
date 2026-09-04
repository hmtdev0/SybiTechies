<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            ['photo' => 'assets/images/testimonials/client-1.jpg', 'client_name' => 'Daniel Morgan', 'company' => 'Nexora Retail', 'designation' => 'CEO', 'review' => 'SysbiTechies rebuilt our entire inventory platform. It is fast, reliable and their team truly understood our business. Sales operations have never run smoother.'],
            ['photo' => 'assets/images/testimonials/client-2.jpg', 'client_name' => 'Sophia Bennett', 'company' => 'EduSphere', 'designation' => 'Founder', 'review' => 'They delivered our school ERP ahead of schedule with impeccable quality. Communication was clear at every step. A partner we fully trust.'],
            ['photo' => 'assets/images/testimonials/client-3.jpg', 'client_name' => 'Michael Chen', 'company' => 'FinNova', 'designation' => 'CTO', 'review' => 'The custom CRM they built transformed how our sales team works. Clean code, thoughtful UX and rock-solid security. Highly recommended.'],
            ['photo' => 'assets/images/testimonials/client-4.jpg', 'client_name' => 'Emma Watson', 'company' => 'Medicore', 'designation' => 'Director', 'review' => 'Our hospital management software runs flawlessly across departments. Their long-term support has been outstanding and genuinely responsive.'],
            ['photo' => 'assets/images/testimonials/client-5.jpg', 'client_name' => 'James Carter', 'company' => 'Logiflow', 'designation' => 'Owner', 'review' => 'From discovery to deployment, everything was professional and transparent. The logistics dashboard they built saves us hours every day.'],
            ['photo' => 'assets/images/testimonials/client-6.jpg', 'client_name' => 'Olivia Reed', 'company' => 'BrightPay', 'designation' => 'COO', 'review' => 'A phenomenal engineering team. They took our vague idea and turned it into a polished, scalable product our customers love.'],
        ];

        foreach ($reviews as $i => $review) {
            Testimonial::query()->updateOrCreate(
                ['client_name' => $review['client_name']],
                array_merge($review, ['rating' => 5, 'display_order' => $i + 1, 'status' => true])
            );
        }
    }
}
