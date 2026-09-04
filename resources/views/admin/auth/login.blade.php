<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — SysbiTechies</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body class="admin-body">

    <div class="admin-auth">
        <div class="admin-auth__card">
            <div class="admin-auth__brand">
                <span class="admin-sidebar__brand-mark">S</span>
                <span class="admin-sidebar__brand-text">Sysbi<span>Techies</span></span>
            </div>
            <h1 class="admin-auth__title">Admin Login</h1>
            <p class="admin-auth__sub">Sign in to manage your website</p>

            @if(session('success'))
                <div class="admin-alert admin-alert--success mb-4">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="admin-alert admin-alert--danger mb-4">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="admin@gmail.com" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-white-50 small" for="remember">Remember me</label>
                    </div>
                    <a href="{{ route('admin.password.request') }}" class="text-white-50 small text-decoration-none">Forgot password?</a>
                </div>
                <button type="submit" class="btn btn-admin-gradient w-100 py-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                </button>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
