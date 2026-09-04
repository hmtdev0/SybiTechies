<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('manage settings');

        $subscribers = NewsletterSubscriber::query()
            ->when($request->filled('search'), fn ($q) => $q->where('email', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderBy('subscribed_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.newsletter.index', [
            'subscribers' => $subscribers,
            'totalSubscribers' => NewsletterSubscriber::count(),
            'totalSubscribed' => NewsletterSubscriber::where('status', 'subscribed')->count(),
            'totalUnsubscribed' => NewsletterSubscriber::where('status', 'unsubscribed')->count(),
            'breadcrumb' => 'Newsletter',
        ]);
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return back()->with('success', 'Subscriber deleted.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = (array) $request->input('ids', []);
        NewsletterSubscriber::query()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids).' subscribers deleted.');
    }

    public function export(Request $request): Response
    {
        $subscribers = NewsletterSubscriber::query()
            ->when($request->filled('search'), fn ($q) => $q->where('email', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderBy('subscribed_at', 'desc')
            ->get();

        $filename = 'newsletter-subscribers-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($subscribers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Email', 'Status', 'Subscribed At']);

            foreach ($subscribers as $subscriber) {
                fputcsv($handle, [
                    $subscriber->email,
                    $subscriber->status,
                    optional($subscriber->subscribed_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
