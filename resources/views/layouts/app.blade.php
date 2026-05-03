<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find the perfect coffee spot in Tagum City based on your mood">
    <meta property="og:title" content="Tagum Spots - Coffee Finder">
    <meta property="og:image" content="{{ asset('Hero.mp4') }}">
    <title>Tagum Spots - Coffee Finder</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&display=swap" rel="stylesheet">
</head>
<body>

    <section class="hero">
        <video autoplay muted loop playsinline preload="metadata" class="hero-video">
            <source src="{{ asset('Hero.mp4') }}" type="video/mp4" />
        </video>
        
        <nav class="nav">
            <span class="nav-logo">Group 5 × Tagum</span>
            <span class="nav-badge">📍 Tagum City, Davao del Norte</span>
        </nav>

        <div class="hero-center">
            <p class="hero-eyebrow">☕ Your Tagum Locals Coffee</p>
            <h1 class="hero-title">Find Your<br/><em>Perfect Spot</em></h1>
            <p class="hero-sub">curated by Group 5</p>
            <p class="hero-desc">
                Not just a café list it's a mood-matching engine for Tagum's best spaces.
            </p>
            
            <!-- Convert to AJAX form to prevent page reload and maintain search query in the input field -ejie -->
            <form action="/" method="GET" class="search-container" onsubmit="return handleSearch(event)">
                <input type="text" name="search" class="search-input" placeholder="Search a specific café or vibe..." value="{{ $searchQuery }}">
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>
    </section>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="footer-logo">Tagum Spots</div>
        <p>Curated with ☕ by <strong style="color: var(--gold)">Group 5</strong></p>
        <p style="margin-top: 0.5rem; font-size: 0.72rem; opacity: 0.5">BSIT · Tagum City · 2026</p>
    </footer>

    @stack('scripts')
</body>
</html>