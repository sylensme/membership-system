<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Member Desk keeps membership profiles, details, status, and follow-up work together.">
    <title>@yield('title', 'Member Desk')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&family=League+Spartan:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body">
    <div class="app-shell">
        <aside class="app-sidebar" aria-label="Primary navigation">
            <a class="app-brand" href="{{ route('home') }}">
                <span class="app-mark" aria-hidden="true"></span>
                <span>Member Desk</span>
            </a>
            <p class="app-nav-label">Workspace</p>
            <nav class="app-nav">
                <a href="{{ route('home') }}" @if (request()->routeIs('home')) aria-current="page" @endif><span class="nav-symbol" aria-hidden="true"></span>Dashboard</a>
                <a href="{{ route('members.index') }}" @if (request()->routeIs('members.*')) aria-current="page" @endif>
                    <span class="nav-symbol" aria-hidden="true"></span>
                    Members
                </a>
                <a href="{{ route('applications.index') }}" @if (request()->routeIs('applications.*') && !request()->routeIs('applications.create')) aria-current="page" @endif><span class="nav-symbol" aria-hidden="true"></span>Applications</a>
                <a href="{{ route('categories.index') }}" @if (request()->routeIs('categories.*')) aria-current="page" @endif><span class="nav-symbol" aria-hidden="true"></span>Categories</a>
                <a href="{{ route('levels.index') }}" @if (request()->routeIs('levels.*')) aria-current="page" @endif><span class="nav-symbol" aria-hidden="true"></span>Levels</a>
                <a href="{{ route('transactions.index') }}" @if (request()->routeIs('transactions.*') || request()->routeIs('payments.*')) aria-current="page" @endif>
                    <span class="nav-symbol" aria-hidden="true"></span>
                    Transactions
                </a>
                <a href="{{ route('tasks.index') }}" @if (request()->routeIs('tasks.*')) aria-current="page" @endif>
                    <span class="nav-symbol" aria-hidden="true"></span>
                    Tasks
                </a>
            </nav>
            <p class="app-sidebar__foot">Web System Technologies<br>Laravel Foundations</p>
        </aside>

        <header class="app-topbar">
            <span class="app-topbar__context">@yield('context', 'Membership workspace')</span>
            <div class="app-topbar__scope">
                <span class="scope-dot" aria-hidden="true"></span>
                <span class="app-topbar__user">{{ auth()->user()->name }}</span>
                <button class="text-button theme-toggle" type="button" data-theme-toggle aria-label="Switch to dark mode"><span data-theme-label>Dark mode</span></button>
                <form action="{{ route('logout') }}" method="POST">@csrf<button class="text-button" type="submit">Sign out</button></form>
            </div>
        </header>

        <main class="app-main">
            <div class="app-canvas">
                @if (session('success'))
                    <p class="alert success" role="status">{{ session('success') }}</p>
                @endif

                @if ($errors->any())
                    <div class="alert error" role="alert">
                        <strong>Please correct the highlighted fields.</strong>
                        <ul>@foreach ($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
