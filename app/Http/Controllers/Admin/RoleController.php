<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Super Admin is created and fully permissioned by the seeder and
     * bypasses every check via Gate::before — it must never be edited or
     * deleted from the UI, since doing so could strip the one role that's
     * guaranteed to always have panel access.
     */
    protected const PROTECTED_ROLE = 'Super Admin';

    public function index(): View
    {
        $roles = Role::withCount(['permissions', 'users'])->orderBy('name')->get();

        return view('admin.roles.index', [
            'roles' => $roles,
            'protectedRole' => self::PROTECTED_ROLE,
            'breadcrumb' => 'Roles & Permissions',
        ]);
    }

    public function create(): View
    {
        return view('admin.roles.create', [
            'permissionGroups' => RolesAndPermissionsSeeder::PERMISSIONS,
            'assignedPermissions' => [],
            'breadcrumb' => 'Roles — Add New',
        ]);
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        $role = Role::create(['name' => $request->validated('name'), 'guard_name' => 'web']);
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role): View|RedirectResponse
    {
        if ($role->name === self::PROTECTED_ROLE) {
            return redirect()->route('admin.roles.index')->with('error', 'The Super Admin role cannot be edited.');
        }

        return view('admin.roles.edit', [
            'role' => $role,
            'permissionGroups' => RolesAndPermissionsSeeder::PERMISSIONS,
            'assignedPermissions' => $role->permissions->pluck('name')->all(),
            'breadcrumb' => 'Roles — Edit',
        ]);
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        if ($role->name === self::PROTECTED_ROLE) {
            return back()->with('error', 'The Super Admin role cannot be edited.');
        }

        $role->update(['name' => $request->validated('name')]);
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->name === self::PROTECTED_ROLE) {
            return back()->with('error', 'The Super Admin role cannot be deleted.');
        }

        if ($role->users()->exists()) {
            return back()->with('error', 'This role is assigned to one or more users and cannot be deleted. Reassign those users first.');
        }

        $role->delete();

        return back()->with('success', 'Role deleted.');
    }
}
