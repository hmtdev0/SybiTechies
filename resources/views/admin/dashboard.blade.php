@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Welcome back, {{ explode(' ', auth()->user()->name)[0] }} 👋</h2>
            <p class="text-muted mb-0">Here's what's happening with your website today.</p>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="row g-4 mb-4">
        @php
            $statCards = [
                ['label' => 'Total Projects', 'value' => $totalProjects, 'delta' => $newProjectsThisWeek, 'icon' => 'bi-briefcase-fill', 'bg' => 'admin-bg-1', 'link' => route('admin.projects.index'), 'linkLabel' => 'Projects'],
                ['label' => 'Total Services', 'value' => $totalServices, 'delta' => $newServicesThisWeek, 'icon' => 'bi-code-square', 'bg' => 'admin-bg-2', 'link' => route('admin.services.index'), 'linkLabel' => 'Services'],
                ['label' => 'Total Messages', 'value' => $totalMessages, 'delta' => $newMessagesThisWeek, 'icon' => 'bi-envelope-fill', 'bg' => 'admin-bg-3', 'link' => route('admin.messages.index'), 'linkLabel' => 'Messages'],
                ['label' => 'Total Testimonials', 'value' => $totalTestimonials, 'delta' => $newTestimonialsThisWeek, 'icon' => 'bi-chat-quote-fill', 'bg' => 'admin-bg-4', 'link' => route('admin.testimonials.index'), 'linkLabel' => 'Testimonials'],
            ];
        @endphp
        @foreach($statCards as $i => $stat)
        <div class="col-xl-3 col-sm-6">
            <div class="admin-stat-card admin-animate" style="--i: {{ $i }}">
                <div class="admin-stat-card__top">
                    <div class="admin-stat-card__icon {{ $stat['bg'] }}"><i class="bi {{ $stat['icon'] }}"></i></div>
                    @if($stat['delta'] > 0)
                        <span class="admin-stat-card__delta"><i class="bi bi-arrow-up-short"></i>{{ $stat['delta'] }} this week</span>
                    @endif
                </div>
                <div class="admin-stat-card__num" data-count="{{ $stat['value'] }}">0</div>
                <div class="admin-stat-card__label">{{ $stat['label'] }}</div>
                <div class="admin-stat-card__footer">
                    <a href="{{ $stat['link'] }}" class="admin-stat-card__link">View {{ $stat['linkLabel'] }} <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4 mb-4">
        {{-- Recent Contacts --}}
        <div class="col-xl-8">
            <div class="admin-card h-100 admin-animate" style="--i: 4">
                <div class="admin-card__header">
                    <h3 class="admin-card__title">Recent Contacts</h3>
                    <a href="{{ route('admin.messages.index') }}" class="btn-admin-soft btn btn-sm">View All</a>
                </div>
                @if($recentContacts->isEmpty())
                    <div class="admin-empty-state">
                        <div class="admin-empty-state__icon"><i class="bi bi-envelope"></i></div>
                        <p class="admin-empty-state__title">No contact messages yet</p>
                        <p class="admin-empty-state__desc">New enquiries from your website's contact form will show up here.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="admin-table admin-table--stack mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Service</th>
                                    <th>Received</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentContacts as $contact)
                                    <tr class="is-clickable" onclick="window.location='{{ route('admin.messages.show', $contact) }}'">
                                        <td class="admin-table__primary-cell" data-label="Name">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="admin-avatar admin-avatar--sm">{{ strtoupper(substr($contact->name, 0, 1)) }}</span>
                                                <span class="fw-semibold">{{ $contact->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-muted" data-label="Email">{{ $contact->email }}</td>
                                        <td data-label="Service">{{ $contact->service_interested ?: '—' }}</td>
                                        <td class="text-muted" data-label="Received">{{ $contact->created_at->diffForHumans() }}</td>
                                        <td data-label="Status">
                                            @if($contact->is_read)
                                                <span class="admin-pill admin-pill--neutral"><i class="bi bi-check-circle"></i> Read</span>
                                            @else
                                                <span class="admin-pill admin-pill--new"><i class="bi bi-circle-fill" style="font-size:.5rem;"></i> New</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Activity + Quick Actions --}}
        <div class="col-xl-4 d-flex flex-column gap-4">
            <div class="admin-card admin-animate" style="--i: 5">
                <div class="admin-card__header">
                    <h3 class="admin-card__title">Recent Activity</h3>
                </div>
                <div class="admin-card__body">
                    @if($recentActivity->isEmpty())
                        <p class="text-muted small mb-0">Nothing to show yet — activity will appear here as your site gets used.</p>
                    @else
                        <div class="admin-activity">
                            @foreach($recentActivity as $activity)
                                <a href="{{ $activity['link'] }}" class="admin-activity__item">
                                    <span class="admin-activity__icon admin-activity__icon--{{ $activity['color'] }}"><i class="bi {{ $activity['icon'] }}"></i></span>
                                    <div class="min-w-0">
                                        <div class="admin-activity__text">{{ $activity['text'] }}</div>
                                        <div class="admin-activity__time">{{ $activity['time']->diffForHumans() }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="admin-card admin-animate" style="--i: 6">
                <div class="admin-card__header">
                    <h3 class="admin-card__title">Quick Actions</h3>
                </div>
                <div class="admin-card__body">
                    <div class="admin-quick-grid">
                        <a href="{{ route('admin.home-cms.edit') }}" class="admin-quick-tile">
                            <span class="admin-quick-tile__icon"><i class="bi bi-house-door-fill"></i></span>
                            <span class="admin-quick-tile__label">Edit Homepage</span>
                        </a>
                        <a href="{{ route('admin.settings.edit') }}" class="admin-quick-tile">
                            <span class="admin-quick-tile__icon"><i class="bi bi-gear-fill"></i></span>
                            <span class="admin-quick-tile__label">Website Settings</span>
                        </a>
                        <a href="{{ route('admin.seo.index') }}" class="admin-quick-tile">
                            <span class="admin-quick-tile__icon"><i class="bi bi-search"></i></span>
                            <span class="admin-quick-tile__label">Manage SEO</span>
                        </a>
                        <a href="{{ route('admin.projects.create') }}" class="admin-quick-tile">
                            <span class="admin-quick-tile__icon"><i class="bi bi-briefcase-fill"></i></span>
                            <span class="admin-quick-tile__label">Add Project</span>
                        </a>
                        <a href="{{ route('admin.services.create') }}" class="admin-quick-tile">
                            <span class="admin-quick-tile__icon"><i class="bi bi-code-square"></i></span>
                            <span class="admin-quick-tile__label">Add Service</span>
                        </a>
                        <a href="{{ route('admin.testimonials.create') }}" class="admin-quick-tile">
                            <span class="admin-quick-tile__icon"><i class="bi bi-chat-quote-fill"></i></span>
                            <span class="admin-quick-tile__label">Add Testimonial</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="admin-mini-strip admin-animate" style="--i: 7">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Visitor analytics integration coming in a future update.</span>
            </div>
        </div>
    </div>

    {{-- Latest Testimonials + Content Health --}}
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="admin-card h-100 admin-animate" style="--i: 8">
                <div class="admin-card__header">
                    <h3 class="admin-card__title">Latest Testimonials</h3>
                    <a href="{{ route('admin.testimonials.index') }}" class="btn-admin-soft btn btn-sm">View All</a>
                </div>
                <div class="admin-card__body">
                    @if($latestTestimonials->isEmpty())
                        <p class="text-muted small mb-0">No testimonials yet.</p>
                    @else
                        <div class="admin-testimonial-mini">
                            @foreach($latestTestimonials as $testimonial)
                                <div class="admin-testimonial-mini__item">
                                    <span class="admin-avatar admin-avatar--sm">
                                        @if($testimonial->photo)
                                            <img src="{{ asset($testimonial->photo) }}" alt="{{ $testimonial->client_name }}">
                                        @else
                                            {{ strtoupper(substr($testimonial->client_name, 0, 1)) }}
                                        @endif
                                    </span>
                                    <div class="min-w-0">
                                        <div class="admin-testimonial-mini__stars">
                                            @for($s = 0; $s < 5; $s++)
                                                <i class="bi {{ $s < $testimonial->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="admin-testimonial-mini__text">"{{ $testimonial->review }}"</p>
                                        <div class="admin-testimonial-mini__author">{{ $testimonial->client_name }}@if($testimonial->company), {{ $testimonial->company }}@endif</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="admin-card h-100 admin-animate" style="--i: 9">
                <div class="admin-card__header">
                    <h3 class="admin-card__title">Content Health</h3>
                </div>
                <div class="admin-card__body">
                    <div class="admin-health-list">
                        @foreach($contentHealth as $check)
                            <a href="{{ $check['link'] }}" class="admin-health-item {{ $check['count'] === 0 ? 'admin-health-item--ok' : '' }}">
                                <i class="bi {{ $check['count'] === 0 ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' }} admin-health-item__icon {{ $check['count'] === 0 ? 'admin-health-item__icon--ok' : 'admin-health-item__icon--warn' }}"></i>
                                <span class="admin-health-item__label">{{ $check['label'] }}</span>
                                <span class="admin-health-item__count">{{ $check['count'] === 0 ? 'All good' : $check['count'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
