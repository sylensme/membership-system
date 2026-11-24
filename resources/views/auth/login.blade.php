<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in · Member Desk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&family=League+Spartan:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body login-body">
    <button class="text-button theme-toggle login-theme-toggle" type="button" data-theme-toggle aria-label="Switch to dark mode"><span data-theme-label>Dark mode</span></button>
    <main class="login-panel">
        <a class="app-brand" href="{{ route('login') }}"><span class="app-mark" aria-hidden="true"></span><span>Member Desk</span></a>
        <p class="eyebrow">Administrator access</p><h1>Sign in</h1><p class="muted">Manage member records, applications, and payments from one workspace.</p>
        @if (app()->environment('local'))
            <div class="login-info">
                <button class="login-info-toggle" type="button" data-login-info-toggle aria-expanded="false" aria-controls="login-info-panel">Show demo login info</button>
                <div class="login-info-panel" id="login-info-panel" hidden>
                    <p>Use this demo administrator account for your class presentation.</p>
                    <dl>
                        <dt>Admin email</dt><dd><code>test@example.com</code></dd>
                        <dt>Password</dt><dd><code>password</code></dd>
                    </dl>
                </div>
            </div>
        @endif
        @if ($errors->any())<div class="alert error" role="alert">{{ $errors->first() }}</div>@endif
        <form action="{{ route('login.store') }}" method="POST">
            @csrf
            <div class="field"><label for="email">Email address</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>@error('email')<p class="error-text">{{ $message }}</p>@enderror</div>
            <div class="field"><label for="password">Password</label><input id="password" name="password" type="password" autocomplete="current-password" required>@error('password')<p class="error-text">{{ $message }}</p>@enderror</div>
            <label class="remember-field"><input name="remember" type="checkbox" value="1"> Keep me signed in</label>
            <button class="button" type="submit">Sign in</button>
        </form>
    </main>
</body>
</html>
