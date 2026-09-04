<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuItemRequest;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $this->authorize('manage settings');

        $menus = Menu::query()->with('items')->get()->keyBy('location');

        return view('admin.menus.index', [
            'menus' => $menus,
            'breadcrumb' => 'Menu Manager',
        ]);
    }

    public function store(MenuItemRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['parent_id'] = null;
        $data['is_new_tab'] = $request->boolean('is_new_tab');
        $data['status'] = $request->boolean('status', true);

        MenuItem::create($data);

        return back()->with('success', 'Menu item added successfully.');
    }

    public function update(MenuItemRequest $request, MenuItem $menuItem): RedirectResponse
    {
        $data = $request->validated();
        $data['parent_id'] = null;
        $data['is_new_tab'] = $request->boolean('is_new_tab');
        $data['status'] = $request->boolean('status', true);

        $menuItem->update($data);

        return back()->with('success', 'Menu item updated successfully.');
    }

    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->delete();

        return back()->with('success', 'Menu item deleted.');
    }

    public function toggleStatus(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->update(['status' => ! $menuItem->status]);

        return back()->with('success', 'Status updated.');
    }
}
