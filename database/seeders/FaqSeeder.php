<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['question' => 'What services does SysbiTechies offer?', 'answer' => 'We build custom software, websites, mobile apps, desktop applications, ERP and CRM systems, plus AI-driven automation for businesses of every size.'],
            ['question' => 'How long does a typical project take?', 'answer' => 'Timelines vary by scope, but most projects launch within 6-12 weeks. We share a clear roadmap with milestones during the Planning phase.'],
            ['question' => 'Do you provide support after launch?', 'answer' => 'Yes — every project includes a support window, and we offer ongoing maintenance plans for ports, updates and monitoring.'],
            ['question' => 'Can you work with our existing team?', 'answer' => 'Absolutely. We regularly integrate with in-house developers and product teams, adapting to your existing workflow and tools.'],
            ['question' => 'What industries do you have experience in?', 'answer' => 'We have delivered projects across healthcare, education, retail, real estate, finance, logistics and more.'],
            ['question' => 'How do we get started?', 'answer' => 'Reach out through our contact form with a short project brief, and our team will schedule a discovery call within one business day.'],
        ];

        foreach ($faqs as $i => $faq) {
            Faq::query()->updateOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, ['display_order' => $i + 1, 'status' => true])
            );
        }
    }
}
