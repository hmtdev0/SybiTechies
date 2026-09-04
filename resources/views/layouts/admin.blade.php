<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — SysbiTechies Admin</title>
    <meta name="robots" content="noindex, nofollow">

    <script>
        // Applied before first paint (not in admin.js, which loads at the
        // end of body) so the page never flashes light before switching
        // to a previously-chosen dark theme.
        (function () {
            var stored = localStorage.getItem('admin_theme');
            var theme = stored || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}?v={{ filemtime(public_path('assets/css/admin.css')) }}">

    @stack('styles')
</head>
<body class="admin-body">

    <div class="admin-wrapper">
        @include('admin.partials.sidebar')

        <div class="admin-main">
            @include('admin.partials.topbar')

            <main class="admin-content">
                @include('admin.partials.flash-messages')
                @yield('content')
            </main>
        </div>
    </div>

    @include('admin.partials.confirm-delete-modal')

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/js/admin.js') }}?v={{ filemtime(public_path('assets/js/admin.js')) }}"></script>
    @stack('scripts')
</body>
</html>
