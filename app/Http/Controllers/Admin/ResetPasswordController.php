<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function show(Request $request, string $token): View
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            },
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withErrors(['email' => __($status)])->onlyInput('email');
        }

        return redirect()->route('admin.login')->with('success', 'Your password has been reset. You can now log in.');
    }
}
