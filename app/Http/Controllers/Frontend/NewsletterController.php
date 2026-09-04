<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:150'],
        ]);

        NewsletterSubscriber::query()->updateOrCreate(
            ['email' => $data['email']],
            ['status' => 'subscribed', 'subscribed_at' => now()]
        );

        return back()->with('success', 'Thanks for subscribing! You will hear from us soon.')->withFragment('footer');
    }
}
