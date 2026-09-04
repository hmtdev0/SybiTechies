<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Defines every role and permission for the admin panel. Safe to re-run:
 * findOrCreate() is idempotent and syncPermissions() always sets the exact
 * list below, so running this again after adding a new permission to a
 * module's array is the supported way to roll it out to existing roles.
 *
 * See "Adding a new permission" at the bottom of this file.
 */
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Every permission in the system, grouped by module. The group key is
     * only used for the admin UI's checkbox grid — Spatie itself stores a
     * flat list of permission names.
     */
    public const PERMISSIONS = [
        'Services' => ['view services', 'create services', 'edit services', 'delete services', 'publish services'],
        'Portfolio' => ['view portfolio', 'create portfolio', 'edit portfolio', 'delete portfolio', 'publish portfolio'],
        'Blogs' => ['view blogs', 'create blogs', 'edit blogs', 'delete blogs', 'publish blogs'],
        // 'publish testimonials' isn't in the original spec's example list,
        // but it's required to enforce "Editor ... cannot delete or publish"
        // (Editor is explicitly allowed to create/edit testimonials) —
        // without it there'd be no permission to withhold from Editor.
        'Testimonials' => ['view testimonials', 'create testimonials', 'edit testimonials', 'delete testimonials', 'publish testimonials'],
        'Team' => ['view team', 'create team', 'edit team', 'delete team'],
        'Inquiries' => ['view inquiries', 'delete inquiries', 'reply inquiries'],
        'Users' => ['view users', 'create users', 'edit users', 'delete users'],
        'Roles' => ['manage roles'],
        'Settings' => ['manage settings'],
        'Media' => ['upload media', 'delete media'],
    ];

    public function run(): void
    {
        // Clear Spatie's cached permission map — otherwise a re-run in the
        // same request/process can miss permissions created moments ago.
        Artisan::call('permission:cache-reset');

        $all = collect(self::PERMISSIONS)->flatten()->all();

        foreach ($all as $name) {
            Permission::findOrCreate($name);
        }

        // Super Admin: every permission is attached (for a complete, honest
        // permission matrix in the UI), but access is really guaranteed by
        // the Gate::before bypass in AppServiceProvider — this list is
        // belt-and-suspenders, not the actual access mechanism.
        Role::findOrCreate('Super Admin')->syncPermissions($all);

        // Admin: everything except role management, and cannot touch
        // Super Admin accounts (enforced in code, not permissions).
        Role::findOrCreate('Admin')->syncPermissions(
            collect($all)->reject(fn ($name) => $name === 'manage roles')->all()
        );

        // Editor: can create/edit the four content types, never delete or
        // publish, and can upload (not delete) media for their own content.
        Role::findOrCreate('Editor')->syncPermissions([
            'view services', 'create services', 'edit services',
            'view portfolio', 'create portfolio', 'edit portfolio',
            'view blogs', 'create blogs', 'edit blogs',
            'view testimonials', 'create testimonials', 'edit testimonials',
            'upload media',
        ]);

        // Viewer: read-only across every content module that has a "view"
        // permission (Roles/Settings/Media have no separate view permission
        // in this scheme, so Viewer has no access to those at all).
        Role::findOrCreate('Viewer')->syncPermissions([
            'view services', 'view portfolio', 'view blogs',
            'view testimonials', 'view team', 'view inquiries', 'view users',
        ]);
    }
}

/*
|--------------------------------------------------------------------------
| Adding a new permission
|--------------------------------------------------------------------------
| 1. Add the permission name to the relevant module array in PERMISSIONS
|    above (or add a new module key).
| 2. Add it to whichever role's syncPermissions() list should have it.
| 3. Re-run: php artisan db:seed --class=RolesAndPermissionsSeeder
|    (safe on production data — only touches the roles/permissions tables).
| 4. Gate it wherever needed: $this->authorize('your permission name') in a
|    controller, or middleware('permission:your permission name') on a
|    route, or @can('your permission name') in a Blade view.
*/
