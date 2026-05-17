<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $shop->name }} — Group 5</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&display=swap" rel="stylesheet">
    
    <style>
        /* Exact same styles as admin.blade.php to maintain brand consistency */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --bg: #fdf8f3; --surface: #ffffff; --surface-alt: #f5ede0; --border: rgba(168,144,110,0.3); --border-strong: #a8906e; --espresso: #1c0d00; --caramel: #c9873a; --caramel-light: rgba(201,135,58,0.15); --text-primary: #2e1503; --text-secondary: #7a5c3d; --text-muted: #a8906e; --radius-sm: 6px; --radius-md: 12px; --radius-lg: 20px; --shadow-card: 0 8px 40px rgba(28,13,0,0.08); }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text-primary); padding-bottom: 5rem; }
        .nav { background: var(--espresso); padding: 0 2rem; height: 60px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 10; }
        .nav-logo { font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--text-muted); text-transform: uppercase; }
        .btn-back { font-size: 12px; font-weight: 600; color: var(--surface-alt); background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: var(--radius-sm); padding: 8px 16px; cursor: pointer; text-decoration: none; }
        .page { max-width: 760px; margin: 0 auto; padding: 3rem 1.5rem 0; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 700; color: var(--espresso); line-height: 1.2; margin-bottom: 2rem; }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow-card); margin-bottom: 1.5rem; overflow: hidden; }
        .card-header { padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; background: #fffbf6; }
        .card-icon { width: 36px; height: 36px; border-radius: var(--radius-sm); background: var(--surface-alt); display: flex; align-items: center; justify-content: center; font-size: 16px; }
        .card-title { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; }
        .card-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.2rem; }
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .field { display: flex; flex-direction: column; gap: 6px; }
        .field-label { font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-secondary); }
        input[type="text"], input[type="url"] { width: 100%; font-family: 'DM Sans', sans-serif; font-size: 14px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 12px; outline: none; }
        input:focus { border-color: var(--caramel); box-shadow: 0 0 0 3px var(--caramel-light); }
        .mood-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .mood-option { display: none; }
        .mood-label { display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 1rem; border: 2px solid transparent; border-radius: var(--radius-md); cursor: pointer; background: var(--surface-alt); }
        .mood-option:checked + .mood-label { border-color: var(--caramel); background: #fff; box-shadow: var(--shadow-card); }
        .tag-grid { display: flex; flex-wrap: wrap; gap: 8px; }
        .tag-checkbox { display: none; }
        .tag-pill { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border: 1px solid var(--border); border-radius: 100px; font-size: 12px; font-weight: 500; color: var(--text-secondary); cursor: pointer; background: var(--surface); }
        .tag-checkbox:checked + .tag-pill { background: var(--espresso); border-color: var(--espresso); color: var(--surface-alt); }
        .btn-deploy { width: 100%; padding: 1rem; background: var(--caramel); color: var(--espresso); border: none; border-radius: 100px; font-family: 'DM Sans', sans-serif; font-size: 1rem; font-weight: 700; cursor: pointer; transition: 0.3s; margin-top: 1rem; }
        .btn-deploy:hover { background: #e8a84e; transform: translateY(-2px); }
    </style>
</head>
<body>

<nav class="nav">
    <div class="nav-logo">Group 5 × Tagum</div>
    <a href="{{ route('admin.index') }}" class="btn-back">← Cancel & Return</a>
</nav>

<div class="page">
    <h1 class="page-title">Editing: <br><em style="color: var(--caramel);">{{ $shop->name }}</em></h1>

    <form action="{{ route('shops.update', $shop->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header"><div class="card-icon">🏷️</div><div class="card-title">Core Identity</div></div>
            <div class="card-body">
                <div class="field">
                    <label class="field-label">Shop Name</label>
                    <input type="text" name="name" value="{{ $shop->name }}" required>
                </div>
                <div class="field">
                    <label class="field-label">Mood Category</label>
                    <div class="mood-grid">
                        <div>
                            <input class="mood-option" type="radio" name="mood" value="work" id="mood-work" {{ $shop->mood == 'work' ? 'checked' : '' }}>
                            <label class="mood-label" for="mood-work">💻 Focus</label>
                        </div>
                        <div>
                            <input class="mood-option" type="radio" name="mood" value="eat" id="mood-eat" {{ $shop->mood == 'eat' ? 'checked' : '' }}>
                            <label class="mood-label" for="mood-eat">🍰 Cravings</label>
                        </div>
                        <div>
                            <input class="mood-option" type="radio" name="mood" value="chill" id="mood-chill" {{ $shop->mood == 'chill' ? 'checked' : '' }}>
                            <label class="mood-label" for="mood-chill">✨ Aesthetic</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-icon">🎨</div><div class="card-title">Brand Assets</div></div>
            <div class="card-body">
                <div class="field-row">
                    <div class="field">
                        <label class="field-label">Badge</label>
                        <input type="text" name="badge" value="{{ $shop->badge }}" required>
                    </div>
                    <div class="field">
                        <label class="field-label">Emoji</label>
                        <input type="text" name="emoji" value="{{ $shop->emoji }}" required>
                    </div>
                </div>
                <div class="field">
                    <label class="field-label">Tagline</label>
                    <input type="text" name="tagline" value="{{ $shop->tagline }}" required>
                </div>
                <div class="field">
                    <label class="field-label">Image URL</label>
                    <input type="url" name="image_url" value="{{ $shop->image_url }}" required>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-icon">⚙️</div><div class="card-title">Amenities & Features</div></div>
            <div class="card-body">
                <div class="tag-grid">
                    @php
                        $availableTags = [
                            'Fast Wi-Fi', 'Many Outlets', 'Quiet Vibe', 'Aircon', 'Specialty Coffee', 
                            'Study Tables', 'Signature Cakes', 'Milktea', 'Instagrammable', 'Plush Seating'
                        ];
                    @endphp
                    @foreach($availableTags as $tagName)
                        <input class="tag-checkbox" type="checkbox" name="tags[]" value="{{ $tagName }}" id="tag-{{ $loop->index }}" {{ in_array($tagName, $activeTags) ? 'checked' : '' }}>
                        <label class="tag-pill" for="tag-{{ $loop->index }}">{{ $tagName }}</label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-icon">📍</div><div class="card-title">Utility & Conversion</div></div>
            <div class="card-body">
                <div class="field">
                    <label class="field-label">Must Try Item</label>
                    <input type="text" name="must_try" value="{{ $shop->must_try }}" required>
                </div>
                <div class="field">
                    <label class="field-label">Google Maps URL</label>
                    <input type="url" name="maps_url" value="{{ $shop->maps_url }}" required>
                </div>
            </div>
        </div>

        <button class="btn-deploy" type="submit">Save Changes</button>
    </form>
</div>
</body>
</html>