<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — SysbiTechies Admin</title>
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
            <h1 class="admin-auth__title">Reset Password</h1>
            <p class="admin-auth__sub">Choose a new password for your admin account</p>

            @if($errors->any())
                <div class="admin-alert admin-alert--danger mb-4">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('admin.password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $email) }}" placeholder="admin@gmail.com" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required minlength="8">
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••" required minlength="8">
                </div>
                <button type="submit" class="btn btn-admin-gradient w-100 py-2">
                    <i class="bi bi-shield-lock-fill me-2"></i>Reset Password
                </button>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
