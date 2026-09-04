<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailSetting;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    public function notice(): View|RedirectResponse
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.verify-email', [
            'canSendMail' => EmailSetting::current()->canSendMail(),
        ]);
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        if (! $request->user()->hasVerifiedEmail()) {
            $request->user()->markEmailAsVerified();
        }

        return redirect()->route('admin.dashboard')->with('success', 'Your email address has been verified.');
    }

    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('admin.dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'A new verification link has been sent to your email address.');
    }
}
