<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied — SysbiTechies Admin</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body class="admin-body">

    <div class="admin-auth">
        <div class="admin-auth__card text-center">
            <div class="admin-auth__brand">
                <span class="admin-sidebar__brand-mark">S</span>
                <span class="admin-sidebar__brand-text">Sysbi<span>Techies</span></span>
            </div>

            <div class="mb-3" style="font-size: 3rem; color: #EF4444;">
                <i class="bi bi-shield-lock-fill"></i>
            </div>

            <h1 class="admin-auth__title">Access Denied</h1>
            <p class="admin-auth__sub mb-4">
                {{ $exception->getMessage() ?: "You don't have permission to view this page. If you think this is a mistake, contact your administrator." }}
            </p>

            @auth
                <a href="{{ route('admin.dashboard') }}" class="btn btn-admin-gradient w-100 py-2">
                    <i class="bi bi-house-door-fill me-2"></i>Back to Dashboard
                </a>
            @else
                <a href="{{ route('admin.login') }}" class="btn btn-admin-gradient w-100 py-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Go to Login
                </a>
            @endauth
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
