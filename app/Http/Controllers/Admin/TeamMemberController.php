<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamMemberRequest;
use App\Models\TeamMember;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function __construct(protected ImageUploadService $uploads) {}

    public function index(Request $request): View
    {
        $this->authorize('view team');

        $members = TeamMember::query()
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('display_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.team.index', [
            'members' => $members,
            'breadcrumb' => 'Team Members',
        ]);
    }

    public function create(): View
    {
        $this->authorize('create team');

        return view('admin.team.create', ['breadcrumb' => 'Team Members — Add New']);
    }

    public function store(TeamMemberRequest $request): RedirectResponse
    {
        $this->authorize('create team');

        $data = $request->safe()->except(['photo']);
        $data['status'] = $request->boolean('status', true);

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->uploads->store($request->file('photo'), 'team');
        }

        TeamMember::create($data);

        return redirect()->route('admin.team.index')->with('success', 'Team member added successfully.');
    }

    public function edit(TeamMember $team): View
    {
        $this->authorize('edit team');

        return view('admin.team.edit', [
            'member' => $team,
            'breadcrumb' => 'Team Members — Edit',
        ]);
    }

    public function update(TeamMemberRequest $request, TeamMember $team): RedirectResponse
    {
        $this->authorize('edit team');

        $data = $request->safe()->except(['photo']);
        $data['status'] = $request->boolean('status', true);

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->uploads->replace($team->photo, $request->file('photo'), 'team');
        }

        $team->update($data);

        return redirect()->route('admin.team.index')->with('success', 'Team member updated successfully.');
    }

    public function destroy(TeamMember $team): RedirectResponse
    {
        $this->authorize('delete team');

        $this->uploads->delete($team->photo);
        $team->delete();

        return back()->with('success', 'Team member deleted.');
    }

    public function toggleStatus(TeamMember $team): RedirectResponse
    {
        $this->authorize('edit team');

        $team->update(['status' => ! $team->status]);

        return back()->with('success', 'Status updated.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $this->authorize('delete team');

        $ids = (array) $request->input('ids', []);
        $members = TeamMember::query()->whereIn('id', $ids)->get();

        foreach ($members as $member) {
            $this->uploads->delete($member->photo);
        }

        TeamMember::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' team members deleted.');
    }
}
