@php
    // Each item: [label, icon, route name, badge, permission]
    // permission: null = always visible to any panel user, a string = single
    // permission (@can), an array = visible if the user has ANY of them (@canany).
    $navGroups = [
        'Overview' => [['Dashboard', 'bi-grid-1x2-fill', 'admin.dashboard', null, null]],
        'Content' => [
            ['Website Settings', 'bi-gear-fill', 'admin.settings.edit', null, 'manage settings'],
            ['SEO Manager', 'bi-search', 'admin.seo.index', null, 'manage settings'],
            ['Home Page', 'bi-house-door-fill', 'admin.home-cms.edit', null, 'manage settings'],
            ['About Page', 'bi-info-circle-fill', 'admin.about-cms.edit', null, 'manage settings'],
        ],
        'Modules' => [
            ['Services', 'bi-code-square', 'admin.services.index', null, 'view services'],
            ['Projects', 'bi-briefcase-fill', 'admin.projects.index', null, 'view portfolio'],
            ['Categories', 'bi-tags-fill', 'admin.project-categories.index', null, 'manage settings'],
            ['Technologies', 'bi-cpu-fill', 'admin.technologies.index', null, 'manage settings'],
            ['Process Steps', 'bi-diagram-3-fill', 'admin.process-steps.index', null, 'manage settings'],
            ['Industries', 'bi-building', 'admin.industries.index', null, 'manage settings'],
            ['Testimonials', 'bi-chat-quote-fill', 'admin.testimonials.index', null, 'view testimonials'],
            ['Team', 'bi-people-fill', 'admin.team.index', null, 'view team'],
            ['FAQs', 'bi-question-circle-fill', 'admin.faqs.index', null, 'manage settings'],
            ['Blog Posts', 'bi-journal-text', 'admin.blog-posts.index', null, 'view blogs'],
            ['Blog Categories', 'bi-journal-bookmark-fill', 'admin.blog-categories.index', null, 'manage settings'],
        ],
        'Careers' => [
            ['Job Openings', 'bi-person-workspace', 'admin.job-openings.index', null, 'manage settings'],
            ['Applications', 'bi-file-earmark-person-fill', 'admin.job-applications.index', null, 'manage settings'],
        ],
        'Communication' => [
            ['Contact Messages', 'bi-envelope-fill', 'admin.messages.index', null, 'view inquiries'],
            ['Newsletter', 'bi-megaphone-fill', 'admin.newsletter.index', null, 'manage settings'],
            ['Email Settings', 'bi-envelope-at-fill', 'admin.email-settings.edit', null, 'manage settings'],
        ],
        'System' => [
            ['Media Library', 'bi-images', 'admin.media.index', null, ['upload media', 'delete media']],
            ['Menu Manager', 'bi-list-ul', 'admin.menus.index', null, 'manage settings'],
            ['Custom Scripts', 'bi-code-slash', 'admin.custom-scripts.index', null, 'manage settings'],
            ['Users', 'bi-person-badge-fill', 'admin.users.index', null, 'view users'],
            ['Roles & Permissions', 'bi-shield-lock-fill', 'admin.roles.index', null, 'manage roles'],
        ],
    ];

    $currentUser = auth()->user();
@endphp
<aside class="admin-sidebar">
    <a href="{{ route('admin.dashboard') }}" class="admin-sidebar__brand">
        <span class="admin-sidebar__brand-mark">S</span>
        <span class="admin-sidebar__brand-text">Sysbi<span>Techies</span></span>
    </a>

    <nav class="admin-sidebar__nav">
        @foreach ($navGroups as $group => $items)
            @php
                $visibleItems = collect($items)->filter(function ($item) {
                    [, , , , $permission] = $item;

                    return match (true) {
                        $permission === null => true,
                        is_array($permission) => auth()->user()->canAny($permission),
                        default => auth()->user()->can($permission),
                    };
                });
            @endphp
            @continue($visibleItems->isEmpty())

            <div class="admin-sidebar__group-label">{{ $group }}</div>
            @foreach ($visibleItems as [$label, $icon, $routeName, $badge])
                @php $isBuilt = Route::has($routeName); @endphp
                <a href="{{ $isBuilt ? route($routeName) : '#' }}"
                    class="admin-nav-link {{ $isBuilt && request()->routeIs($routeName . '*') ? 'active' : '' }} {{ $isBuilt ? '' : 'disabled' }}"
                    data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="{{ $label }}">
                    <i class="bi {{ $icon }}"></i>
                    <span class="admin-nav-link__label">{{ $label }}</span>
                    @if ($badge)
                        <span class="admin-nav-link__badge">{{ $badge }}</span>
                    @endif
                </a>
            @endforeach
        @endforeach
    </nav>

    <div class="admin-sidebar__footer">
        <a href="{{ route('admin.profile.edit') }}" class="admin-sidebar__user" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="{{ $currentUser->name ?? 'Admin' }}">
            <span class="admin-sidebar__user-avatar">
                @if($currentUser?->avatar)
                    <img src="{{ asset($currentUser->avatar) }}" alt="{{ $currentUser->name }}">
                @else
                    {{ strtoupper(substr($currentUser->name ?? 'A', 0, 1)) }}
                @endif
            </span>
            <span class="admin-sidebar__user-info">
                <span class="admin-sidebar__user-name">{{ $currentUser->name ?? 'Admin' }}</span>
                <span class="admin-sidebar__user-role">{{ $currentUser->getRoleNames()->implode(', ') ?: 'No role' }}</span>
            </span>
        </a>
    </div>
</aside>
<div class="admin-sidebar-backdrop"></div>
