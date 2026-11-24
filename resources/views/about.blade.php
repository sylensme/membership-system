<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About · Member Desk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&family=League+Spartan:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="public-page">
    <main class="public-simple">
        <div class="site-frame">
            <a class="brand" href="{{ route('home') }}">Member Desk</a>
            <h1>{{ $course }}</h1>
            <p>{{ $module }} is the course context behind this small membership workspace.</p>
            <a class="motif-button" href="{{ route('home') }}">Back home</a>
        </div>
    </main>
</body>
</html>
