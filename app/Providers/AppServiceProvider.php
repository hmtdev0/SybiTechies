<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // The whole site (admin + public) is Bootstrap 5 only, no Tailwind —
        // without this, {{ $paginator->links() }} renders Laravel's default
        // Tailwind-utility-class pagination view, which has no effect here.
        Paginator::useBootstrapFive();

        // Super Admin bypasses every permission check outright. Returning
        // null (not false) for everyone else lets Spatie's own Gate::before
        // hook run next and decide based on actual permissions.
        Gate::before(fn (User $user) => $user->hasRole('Super Admin') ? true : null);

        // When served behind an HTTPS proxy (ngrok / live domain), make every
        // generated URL — including asset() links — use https so the browser
        // does not block them as mixed content. Plain http localhost is untouched.
        $request = request();

        $forwardedProto = $request->server('HTTP_X_FORWARDED_PROTO');
        $isSecure = $request->isSecure()
            || $forwardedProto === 'https'
            || str_contains((string) $request->getHost(), 'ngrok');

        if ($isSecure) {
            URL::forceScheme('https');
        }
    }
}
