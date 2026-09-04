<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        // photo is intentionally null — the frontend renders a gradient
        // initials avatar until a real photo is uploaded via the admin panel.
        $team = [
            ['name' => 'William Damian', 'position' => 'Founder & CEO', 'bio' => 'Leads product strategy and client partnerships across every engagement.'],
            ['name' => 'Harry Callum', 'position' => 'Lead Web Developer', 'bio' => 'Architects scalable Laravel applications and mentors the engineering team.'],
            ['name' => 'Matthew Hunter', 'position' => 'UI/UX Designer', 'bio' => 'Designs premium, conversion-focused interfaces for web and mobile products.'],
            ['name' => 'James Thomas', 'position' => 'Marketing Head', 'bio' => 'Drives growth strategy and client acquisition for SysbiTechies.'],
        ];

        foreach ($team as $i => $member) {
            TeamMember::query()->updateOrCreate(
                ['name' => $member['name']],
                array_merge($member, [
                    'photo' => null,
                    'display_order' => $i + 1,
                    'status' => true,
                ])
            );
        }
    }
}
