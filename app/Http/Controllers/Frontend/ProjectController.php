<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::query()
            ->active()
            ->ordered()
            ->with(['category', 'technologies'])
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->whereHas('category', fn ($q2) => $q2->where('slug', $request->string('category')));
            })
            ->paginate(9)
            ->withQueryString();

        return view('pages.projects-index', [
            'seo' => SeoMeta::forPageKey('projects'),
            'siteSettings' => SiteSetting::current(),

            'projects' => $projects,
            'categories' => ProjectCategory::query()->active()->ordered()->get(),
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless($project->status, 404);

        return view('pages.project-show', [
            'seo' => $project->seo ?? new \App\Models\SeoMeta(),
            'siteSettings' => SiteSetting::current(),

            'project' => $project->load(['category', 'images', 'features', 'technologies']),
            'otherProjects' => Project::query()
                ->active()
                ->ordered()
                ->where('id', '!=', $project->id)
                ->when($project->project_category_id, fn ($q) => $q->where('project_category_id', $project->project_category_id))
                ->limit(3)
                ->get(),
        ]);
    }
}
