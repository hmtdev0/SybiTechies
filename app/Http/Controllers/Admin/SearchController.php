<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Lightweight cross-module search for the admin topbar — not a search
 * index, just a handful of small "like" lookups across the modules
 * editors most often jump to mid-task.
 */
class SearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $term = trim((string) $request->string('q'));

        if (mb_strlen($term) < 2) {
            return response()->json(['results' => []]);
        }

        $like = '%'.$term.'%';

        $projects = Project::query()
            ->where('name', 'like', $like)
            ->limit(4)->get()
            ->map(fn ($p) => ['type' => 'Project', 'icon' => 'bi-briefcase-fill', 'label' => $p->name, 'url' => route('admin.projects.edit', $p)]);

        $services = Service::query()
            ->where('title', 'like', $like)
            ->limit(4)->get()
            ->map(fn ($s) => ['type' => 'Service', 'icon' => 'bi-code-square', 'label' => $s->title, 'url' => route('admin.services.edit', $s)]);

        $messages = ContactMessage::query()
            ->where(fn ($q) => $q->where('name', 'like', $like)->orWhere('email', 'like', $like))
            ->limit(4)->get()
            ->map(fn ($m) => ['type' => 'Message', 'icon' => 'bi-envelope-fill', 'label' => $m->name.' — '.$m->email, 'url' => route('admin.messages.show', $m)]);

        $testimonials = Testimonial::query()
            ->where('client_name', 'like', $like)
            ->limit(4)->get()
            ->map(fn ($t) => ['type' => 'Testimonial', 'icon' => 'bi-chat-quote-fill', 'label' => $t->client_name, 'url' => route('admin.testimonials.edit', $t)]);

        $results = $projects->concat($services)->concat($messages)->concat($testimonials)->values();

        return response()->json(['results' => $results]);
    }
}
