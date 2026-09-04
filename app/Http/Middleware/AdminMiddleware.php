<?php

namespace App\Http\Middleware;

use App\Models\EmailSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Ensure the authenticated user is an active admin. Assumes this runs
     * after the `auth` middleware, which already guarantees a logged-in user.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || ! $user->is_active || ! $user->hasAdminPanelAccess()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            abort(403, 'You do not have access to the admin panel.');
        }

        // Only enforce verification while mail is actually deliverable —
        // otherwise a user with no way to receive the link would be locked
        // out of the panel entirely.
        if (! $user->hasVerifiedEmail() && EmailSetting::current()->canSendMail()) {
            return redirect()->route('admin.verification.notice');
        }

        return $next($request);
    }
}
