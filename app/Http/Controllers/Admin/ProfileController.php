<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileRequest;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(protected ImageUploadService $uploads) {}

    public function edit(): View
    {
        return view('admin.profile.edit', [
            'user' => Auth::user(),
            'breadcrumb' => 'Profile',
        ]);
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $data = $request->safe()->except(['avatar', 'current_password', 'password', 'password_confirmation']);

        if ($request->filled('password')) {
            $data['password'] = $request->input('password');
        }

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->uploads->replace($user->avatar, $request->file('avatar'), 'users');
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
}
