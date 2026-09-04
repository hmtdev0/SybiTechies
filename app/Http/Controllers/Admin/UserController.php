<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(protected ImageUploadService $uploads) {}

    public function index(Request $request): View
    {
        $this->authorize('view users');

        $users = User::query()
            ->with('roles')
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%'.$request->string('search').'%';
                $q->where(fn ($q2) => $q2->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'breadcrumb' => 'Users',
        ]);
    }

    public function create(): View
    {
        $this->authorize('create users');

        return view('admin.users.create', [
            'breadcrumb' => 'Users — Add New',
            'roles' => Role::orderBy('name')->get(),
            'assignedRoles' => [],
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->authorize('create users');

        $data = $request->safe()->except(['avatar', 'password_confirmation', 'roles']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->uploads->store($request->file('avatar'), 'users');
        }

        $user = User::create($data);
        $user->syncRoles($request->input('roles', []));
        $user->sendEmailVerificationNotification();

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $this->authorize('edit users');

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
            'assignedRoles' => $user->roles->pluck('name')->all(),
            'breadcrumb' => 'Users — Edit',
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('edit users');

        if ($user->hasRole('Super Admin') && ! $request->user()->hasRole('Super Admin')) {
            return back()->with('error', 'Only a Super Admin can edit a Super Admin account.');
        }

        $data = $request->safe()->except(['avatar', 'password', 'password_confirmation', 'roles']);
        $data['is_active'] = $user->id === Auth::id() ? true : $request->boolean('is_active', true);

        if ($request->filled('password')) {
            $data['password'] = $request->input('password');
        }

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->uploads->replace($user->avatar, $request->file('avatar'), 'users');
        }

        $user->update($data);
        $user->syncRoles($request->input('roles', []));

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete users');

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->hasRole('Super Admin') && ! Auth::user()->hasRole('Super Admin')) {
            return back()->with('error', 'Only a Super Admin can delete a Super Admin account.');
        }

        $this->uploads->delete($user->avatar);
        $user->delete();

        return back()->with('success', 'User deleted.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $this->authorize('edit users');

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        if ($user->hasRole('Super Admin') && ! Auth::user()->hasRole('Super Admin')) {
            return back()->with('error', 'Only a Super Admin can deactivate a Super Admin account.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', 'Status updated.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $this->authorize('delete users');

        $actor = Auth::user();

        $ids = collect((array) $request->input('ids', []))
            ->map(fn ($id) => (int) $id)
            ->reject(fn ($id) => $id === Auth::id())
            ->all();

        $users = User::query()->whereIn('id', $ids)->get();

        // Skip Super Admin accounts too when the actor isn't one themselves —
        // same rule as destroy()/toggleStatus(), just applied per-row here.
        if (! $actor->hasRole('Super Admin')) {
            $users = $users->reject(fn (User $user) => $user->hasRole('Super Admin'));
        }

        foreach ($users as $user) {
            $this->uploads->delete($user->avatar);
        }

        User::query()->whereIn('id', $users->pluck('id'))->delete();

        return back()->with('success', $users->count().' users deleted.');
    }
}
