<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email — SysbiTechies Admin</title>
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
            <h1 class="admin-auth__title">Verify Your Email</h1>
            <p class="admin-auth__sub">Confirm {{ auth()->user()->email }} to unlock the admin panel</p>

            @if(session('success'))
                <div class="admin-alert admin-alert--success mb-4">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(! $canSendMail)
                <div class="admin-alert admin-alert--danger mb-4">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>Email sending is currently disabled in Email Settings, so a verification link can't be delivered right now. Contact another admin to enable it.</span>
                </div>
            @else
                <p class="text-white-50 small text-center mb-4">
                    We've sent a verification link to your email address. Click it to confirm your account. Didn't get it? Request another below.
                </p>

                <form action="{{ route('admin.verification.send') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-admin-gradient w-100 py-2">
                        <i class="bi bi-envelope-arrow-up-fill me-2"></i>Resend Verification Email
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.logout') }}" method="POST" class="text-center mt-4">
                @csrf
                <button type="submit" class="btn btn-link text-white-50 small text-decoration-none p-0">
                    <i class="bi bi-box-arrow-right me-1"></i>Log Out
                </button>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
