@php
    $unreadMessages = \App\Models\ContactMessage::unread()->latest()->limit(5)->get();
    $unreadMessagesCount = \App\Models\ContactMessage::unread()->count();
@endphp
<header class="admin-topbar">
    <button type="button" id="sidebarToggle" class="admin-topbar__toggle" aria-label="Toggle sidebar">
        <i class="bi bi-list fs-5"></i>
    </button>

    <nav class="admin-breadcrumb d-none d-md-block">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        @isset($breadcrumb)
            <span class="mx-1">/</span>
            <span class="active">{{ $breadcrumb }}</span>
        @endisset
    </nav>

    <div class="admin-search" id="adminSearch">
        <i class="bi bi-search admin-search__icon"></i>
        <input type="text" class="admin-search__input" id="adminSearchInput" placeholder="Search projects, services, messages..." autocomplete="off">
        <div class="admin-search__results" id="adminSearchResults"></div>
    </div>

    <div class="admin-topbar__right">
        <button type="button" class="admin-icon-btn" id="themeToggle" aria-label="Toggle dark mode">
            <i class="bi bi-moon-stars-fill" id="themeToggleIcon"></i>
        </button>

        <div class="dropdown">
            <button class="admin-icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell-fill"></i>
                @if($unreadMessagesCount > 0)
                    <span class="admin-icon-btn__dot"></span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end p-2" style="min-width: 320px;">
                <h6 class="dropdown-header d-flex align-items-center justify-content-between">
                    Notifications
                    @if($unreadMessagesCount > 0)
                        <span class="admin-badge admin-badge--info">{{ $unreadMessagesCount }} new</span>
                    @endif
                </h6>
                @forelse($unreadMessages as $notifMessage)
                    <a class="dropdown-item rounded-3 py-2" href="{{ route('admin.messages.show', $notifMessage) }}">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-envelope-fill text-primary mt-1"></i>
                            <div class="min-w-0">
                                <div class="fw-semibold small">{{ $notifMessage->name }}</div>
                                <div class="text-muted" style="font-size: .78rem;">{{ \Illuminate\Support\Str::limit($notifMessage->message, 42) }}</div>
                                <div class="text-muted" style="font-size: .72rem;">{{ $notifMessage->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <span class="dropdown-item-text text-muted small py-2">No new notifications</span>
                @endforelse
                @if($unreadMessagesCount > 0)
                    <hr class="dropdown-divider">
                    <a class="dropdown-item rounded-3 py-2 text-center small fw-semibold" href="{{ route('admin.messages.index') }}">View All Messages</a>
                @endif
            </div>
        </div>

        <div class="dropdown">
            <button class="admin-user-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="admin-user-btn__avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                <span class="admin-user-btn__name">{{ auth()->user()->name ?? 'Admin' }}</span>
                <i class="bi bi-chevron-down small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">{{ auth()->user()->email ?? '' }}</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                        <i class="bi bi-person-circle me-2"></i>Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
