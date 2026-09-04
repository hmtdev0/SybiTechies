<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\SeoMeta;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $weekAgo = now()->subWeek();

        return view('admin.dashboard', [
            'totalProjects' => Project::count(),
            'totalServices' => Service::count(),
            'totalMessages' => ContactMessage::count(),
            'totalTestimonials' => Testimonial::count(),

            'newProjectsThisWeek' => Project::where('created_at', '>=', $weekAgo)->count(),
            'newServicesThisWeek' => Service::where('created_at', '>=', $weekAgo)->count(),
            'newMessagesThisWeek' => ContactMessage::where('created_at', '>=', $weekAgo)->count(),
            'newTestimonialsThisWeek' => Testimonial::where('created_at', '>=', $weekAgo)->count(),

            'recentContacts' => ContactMessage::latest()->limit(5)->get(),
            'recentActivity' => $this->buildRecentActivity(),
            'latestTestimonials' => Testimonial::active()->latest()->limit(3)->get(),
            'contentHealth' => $this->buildContentHealth(),
        ]);
    }

    /**
     * There's no dedicated activity_log table — this synthesizes a feed by
     * pulling the most recent rows from a few models and merging them by
     * timestamp, which is enough for a lightweight dashboard glance.
     */
    protected function buildRecentActivity(): Collection
    {
        $messages = ContactMessage::latest()->limit(4)->get()->map(fn ($m) => [
            'icon' => 'bi-envelope-fill',
            'color' => 'primary',
            'text' => 'New message from '.$m->name,
            'time' => $m->created_at,
            'link' => route('admin.messages.show', $m),
        ]);

        $testimonials = Testimonial::latest()->limit(3)->get()->map(fn ($t) => [
            'icon' => 'bi-chat-quote-fill',
            'color' => 'success',
            'text' => 'Testimonial added from '.$t->client_name,
            'time' => $t->created_at,
            'link' => route('admin.testimonials.edit', $t),
        ]);

        $projects = Project::latest('updated_at')->limit(3)->get()->map(fn ($p) => [
            'icon' => 'bi-briefcase-fill',
            'color' => 'info',
            'text' => 'Project "'.$p->name.'" '.($p->created_at->eq($p->updated_at) ? 'created' : 'updated'),
            'time' => $p->updated_at,
            'link' => route('admin.projects.edit', $p),
        ]);

        return $messages->concat($testimonials)->concat($projects)
            ->sortByDesc('time')
            ->take(6)
            ->values();
    }

    /**
     * Real checks computed from existing data — kept small and honest
     * rather than padded out with placeholders that don't reflect
     * anything actually queryable yet (e.g. broken links, page speed).
     */
    protected function buildContentHealth(): array
    {
        $pagesMissingSeo = collect(SeoMeta::PAGE_KEYS)
            ->reject(fn ($key) => SeoMeta::query()->where('page_key', $key)->whereNotNull('title')->exists())
            ->count();

        return [
            [
                'label' => 'Services missing a card image',
                'count' => Service::whereNull('image')->count(),
                'link' => route('admin.services.index'),
            ],
            [
                'label' => 'Projects missing a thumbnail',
                'count' => Project::whereNull('thumbnail')->count(),
                'link' => route('admin.projects.index'),
            ],
            [
                'label' => 'Pages missing an SEO title',
                'count' => $pagesMissingSeo,
                'link' => route('admin.seo.index'),
            ],
        ];
    }
}
