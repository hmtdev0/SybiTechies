<?php

namespace Database\Seeders;

use App\Models\ProcessStep;
use Illuminate\Database\Seeder;

class ProcessStepSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            ['icon' => 'bi-search', 'title' => 'Discovery', 'description' => 'We dive deep into your goals, users and requirements.'],
            ['icon' => 'bi-clipboard-data', 'title' => 'Planning', 'description' => 'Scope, roadmap, architecture and milestones are defined.'],
            ['icon' => 'bi-palette2', 'title' => 'UI/UX Design', 'description' => 'Wireframes and pixel-perfect, user-centric interfaces.'],
            ['icon' => 'bi-code-slash', 'title' => 'Development', 'description' => 'Clean, scalable code built in agile sprints.'],
            ['icon' => 'bi-bug', 'title' => 'Testing', 'description' => 'Rigorous QA to ensure quality, security and speed.'],
            ['icon' => 'bi-rocket-takeoff', 'title' => 'Deployment', 'description' => 'Smooth, zero-downtime launch to production.'],
            ['icon' => 'bi-headset', 'title' => 'Support', 'description' => 'Ongoing maintenance, monitoring and enhancements.'],
        ];

        foreach ($steps as $i => $step) {
            ProcessStep::query()->updateOrCreate(
                ['title' => $step['title']],
                array_merge($step, ['step_number' => $i + 1, 'display_order' => $i + 1])
            );
        }
    }
}
