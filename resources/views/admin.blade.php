<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Coffee Finder Injection</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&display=swap" rel="stylesheet">
    
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            /* Mapped to your existing App.css variables */
            --bg:             #fdf8f3; /* light-cream */
            --surface:        #ffffff; /* card-bg */
            --surface-alt:    #f5ede0; /* cream */
            --border:         rgba(168,144,110,0.3);
            --border-strong:  #a8906e; /* muted */
            --espresso:       #1c0d00;
            --caramel:        #c9873a; /* amber */
            --caramel-light:  rgba(201,135,58,0.15);
            --text-primary:   #2e1503; /* text-main */
            --text-secondary: #7a5c3d; /* text-light */
            --text-muted:     #a8906e;
            --radius-sm:      6px;
            --radius-md:      12px;
            --radius-lg:      20px;
            --shadow-card:    0 8px 40px rgba(28,13,0,0.08);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
            padding: 0;
        }

        /* Top Nav */
        .nav {
            background: var(--espresso);
            padding: 0 2rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .nav-brand { display: flex; align-items: center; gap: 10px; }
        .nav-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            color: var(--text-muted);
            text-transform: uppercase;
        }
        .nav-badge {
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--caramel);
            background: var(--caramel-light);
            padding: 4px 10px;
            border-radius: 100px;
        }
        .btn-back {
            font-size: 12px;
            font-weight: 600;
            color: var(--surface-alt);
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: var(--radius-sm);
            padding: 8px 16px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-back:hover { background: rgba(255,255,255,0.2); }

        /* Page Shell */
        .page { max-width: 760px; margin: 0 auto; padding: 3rem 1.5rem 5rem; }

        /* Page Header */
        .page-header { margin-bottom: 2.5rem; }
        .page-kicker {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--caramel);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .page-kicker::after { content: ''; flex: 1; height: 1px; background: var(--border); }
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--espresso);
            line-height: 1.2;
        }
        .page-title em { font-style: italic; color: var(--caramel); }
        .page-desc { margin-top: 8px; font-size: 14px; color: var(--text-secondary); line-height: 1.6; }

        /* Cards */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .card-header {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fffbf6;
        }
        .card-icon {
            width: 36px; height: 36px;
            border-radius: var(--radius-sm);
            background: var(--surface-alt);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
        }
        .card-title { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; }
        .card-subtitle { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
        .card-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.2rem; }

        /* Form Elements */
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .field { display: flex; flex-direction: column; gap: 6px; }
        .field-label { font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-secondary); letter-spacing: 0.05em; }
        .field-hint { font-size: 11px; color: var(--text-muted); }
        
        input[type="text"], input[type="url"], select {
            width: 100%; font-family: 'DM Sans', sans-serif; font-size: 14px;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 12px; outline: none; transition: all 0.2s;
        }
        input:focus, select:focus { border-color: var(--caramel); box-shadow: 0 0 0 3px var(--caramel-light); }

        /* Mood Grid */
        .mood-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .mood-option { display: none; }
        .mood-label {
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            padding: 1rem; border: 2px solid transparent; border-radius: var(--radius-md);
            cursor: pointer; transition: all 0.2s; text-align: center; background: var(--surface-alt);
        }
        .mood-label:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .mood-option:checked + .mood-label { border-color: var(--caramel); background: #fff; box-shadow: var(--shadow-card); }
        .mood-emoji { font-size: 24px; }
        .mood-name { font-weight: 700; color: var(--espresso); }
        .mood-desc { font-size: 11px; color: var(--text-secondary); }

        /* Tag Grid */
        .tag-grid { display: flex; flex-wrap: wrap; gap: 8px; }
        .tag-checkbox { display: none; }
        .tag-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 14px; border: 1px solid var(--border); border-radius: 100px;
            font-size: 12px; font-weight: 500; color: var(--text-secondary);
            cursor: pointer; transition: all 0.2s; background: var(--surface);
        }
        .tag-pill:hover { border-color: var(--caramel); color: var(--espresso); }
        .tag-checkbox:checked + .tag-pill { background: var(--espresso); border-color: var(--espresso); color: var(--surface-alt); }

        /* Alerts & Buttons */
        .alert-success { background: #d4edda; color: #155724; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-weight: 500; }
        
        .btn-deploy {
            width: 100%; padding: 1rem; background: var(--caramel); color: var(--espresso);
            border: none; border-radius: 100px; font-family: 'DM Sans', sans-serif;
            font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s;
        }
        .btn-deploy:hover { background: #e8a84e; transform: translateY(-2px); box-shadow: 0 8px 30px rgba(201,135,58,0.4); }

        .img-preview { height: 120px; border-radius: var(--radius-md); border: 2px dashed var(--border); display: flex; align-items: center; justify-content: center; background: var(--surface-alt); overflow: hidden; margin-top: 8px;}
        #preview-img { width: 100%; height: 100%; object-fit: cover; display: none; }
    </style>
</head>
<body>

<nav class="nav">
    <div class="nav-brand">
        <span class="nav-logo">Group 5 × Tagum</span>
        <span class="nav-badge">Admin</span>
    </div>
    <a href="/" class="btn-back">← Lock Vault & Return</a>
</nav>

<div class="page">
    <div class="page-header">
        <div class="page-kicker">Database Injection</div>
        <h1 class="page-title">Add a new <em>coffee shop</em></h1>
        <p class="page-desc">Fill in the shop's details below. Once deployed, it will be securely written to your Aiven MySQL database.</p>
    </div>

    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-size: 14px;">
            <strong>⚠️ Deployment Failed:</strong>
            <ul style="margin-top: 8px; margin-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Section: Manage Existing Shops -->
    <div class="card" style="margin-bottom: 3rem;">
        <div class="card-header">
            <div class="card-icon">🗄️</div>
            <div>
                <div class="card-title">Manage Deployed Shops</div>
                <div class="card-subtitle">View, edit, and remove active spots</div>
            </div>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="background: var(--surface-alt); border-bottom: 1px solid var(--border);">
                        <th style="padding: 1rem 1.5rem; color: var(--text-secondary);">ID</th>
                        <th style="padding: 1rem 1.5rem; color: var(--text-secondary);">Shop Name</th>
                        <th style="padding: 1rem 1.5rem; color: var(--text-secondary);">Mood</th>
                        <th style="padding: 1rem 1.5rem; color: var(--text-secondary);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shops as $shop)
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 1rem 1.5rem; font-weight: 600; color: var(--text-muted);">#{{ $shop->id }}</td>
                        <td style="padding: 1rem 1.5rem; font-weight: bold; color: var(--espresso);">{{ $shop->name }}</td>
                        <td style="padding: 1rem 1.5rem;"><span class="tag-pill">{{ ucfirst($shop->mood) }}</span></td>
                        <td style="padding: 1rem 1.5rem; display: flex; gap: 10px;">
                            <a href="{{ route('shops.edit', $shop->id) }}" style="padding: 6px 12px; font-size: 12px; background: #e2e8f0; color: #475569; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-weight: bold; transition: 0.2s;" onmouseover="this.style.background='#cbd5e1'" onmouseout="this.style.background='#e2e8f0'">
                                Edit
                            </a>
                            <button type="button" onclick="openDeleteModal({{ $shop->id }}, '{{ addslashes($shop->name) }}')" style="padding: 6px 12px; font-size: 12px; background: #fee2e2; color: #991b1b; border: none; border-radius: 6px; cursor: pointer; font-family: inherit; transition: 0.2s;" onmouseover="this.style.background='#fca5a5'" onmouseout="this.style.background='#fee2e2'">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach

                    @if($shops->isEmpty())
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-muted);">No shops deployed yet. Your database is empty.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 2rem;">
        <div style="flex: 1; height: 1px; background: var(--border);"></div>
        <div style="font-family: 'Playfair Display'; font-weight: 700; color: var(--caramel);">OR ADD NEW</div>
        <div style="flex: 1; height: 1px; background: var(--border);"></div>
    </div>

    <form action="{{ route('shops.store') }}" method="POST" autocomplete="off">
        @csrf

        <div class="card">
            <div class="card-header">
                <div class="card-icon">🏷️</div>
                <div>
                    <div class="card-title">Core Identity</div>
                    <div class="card-subtitle">Name and mood category</div>
                </div>
            </div>
            <div class="card-body">
                <div class="field">
                    <label class="field-label">Shop Name</label>
                    <input type="text" name="name" placeholder="e.g. Bean O' Clock" required>
                </div>
                <div class="field">
                    <label class="field-label">Mood Category</label>
                    <div class="mood-grid">
                        <div>
                            <input class="mood-option" type="radio" name="mood" value="work" id="mood-work" required>
                            <label class="mood-label" for="mood-work">
                                <span class="mood-emoji">💻</span>
                                <span class="mood-name">Focus</span>
                                <span class="mood-desc">Work & Productivity</span>
                            </label>
                        </div>
                        <div>
                            <input class="mood-option" type="radio" name="mood" value="eat" id="mood-eat">
                            <label class="mood-label" for="mood-eat">
                                <span class="mood-emoji">🍰</span>
                                <span class="mood-name">Cravings</span>
                                <span class="mood-desc">Desserts & Eats</span>
                            </label>
                        </div>
                        <div>
                            <input class="mood-option" type="radio" name="mood" value="chill" id="mood-chill">
                            <label class="mood-label" for="mood-chill">
                                <span class="mood-emoji">✨</span>
                                <span class="mood-name">Aesthetic</span>
                                <span class="mood-desc">Chill & Social</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-icon">🎨</div>
                <div>
                    <div class="card-title">Brand & Visual Assets</div>
                    <div class="card-subtitle">How this shop appears on cards</div>
                </div>
            </div>
            <div class="card-body">
                <div class="field-row">
                    <div class="field">
                        <label class="field-label">Badge</label>
                        <input type="text" name="badge" placeholder="THE CODER'S DEN" required>
                        <span class="field-hint">Short uppercase niche label</span>
                    </div>
                    <div class="field">
                        <label class="field-label">Emoji</label>
                        <input type="text" name="emoji" placeholder="☕" style="font-size: 18px; text-align: center;" required>
                        <span class="field-hint">Fallback if image fails</span>
                    </div>
                </div>
                <div class="field">
                    <label class="field-label">Tagline</label>
                    <input type="text" name="tagline" placeholder="Where deadlines meet espresso shots" required>
                    <span class="field-hint">Italicized micro-copy for brand personality</span>
                </div>
                <div class="field">
                    <label class="field-label">High-Res Image URL</label>
                    <input type="url" name="image_url" placeholder="https://images.unsplash.com/…" oninput="previewImage(this.value)" required>
                    <div class="img-preview" id="img-preview-box">
                        <img id="preview-img" alt="Preview">
                        <span id="img-preview-hint" style="font-size: 12px; color: var(--text-muted);">🖼️ Image preview will appear here</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-icon">⚙️</div>
                <div>
                    <div class="card-title">Amenities & Features</div>
                    <div class="card-subtitle">Stored as JSON array</div>
                </div>
            </div>
            <div class="card-body">
                <div class="tag-grid">
                    @php
                        // Duplicates (like the second 'Aircon' and 'Coffee & Tea') have been purged to prevent rendering bugs.
                        $availableTags = [
                            'Fast Wi-Fi' => '📶', 'Many Outlets' => '🔌', 'Quiet Vibe' => '🤫', 'Aircon' => '❄️',
                            'Wi-Fi Ready' => '📶', 'Charging Points' => '🔌', 'Dim Lighting' => '🕯️',
                            'Ultra-Fast Wi-Fi' => '📶', 'Standing Desk Area' => '🔌', 'Enforced Quiet Zone' => '🤫',
                            'Printer Available' => '🖨️', '50Mbps Wi-Fi' => '📶', 'Outlet-Per-Seat' => '🔌',
                            'Specialty Coffee' => '☕', 'Study Tables' => '📚', 'Signature Cakes' => '🍰',
                            'Milktea' => '🧋', 'Family-Friendly' => '👨‍👩‍👧', 'Pastry Fresh Daily' => '🍰',
                            'Coffee & Tea' => '☕', 'Takeout Boxes' => '🎁', 'Date Spot' => '💕',
                            'Floral Pastries' => '🌸', 'Seasonal Flavors' => '🍓', 'Gift Boxes' => '🎁',
                            'Fresh-Baked Daily' => '🍪', 'Milk Pairings' => '🥛', 'Homey Atmosphere' => '😌',
                            'Cupcakes Too' => '🧁', 'Instagrammable' => '📸', 'Plush Seating' => '🛋️',
                            'Botanical Decor' => '🌿', 'Natural Light' => '🔆', 'Dark Aesthetic' => '🖤',
                            'Candlelit Corners' => '🕯️', 'Curated Playlist' => '🎵', 'Photo Walls' => '📸',
                            'Homey Interiors' => '🏡', 'Bean Bags' => '🛋️', 'Warm Lighting' => '🕯️',
                            'Chill Playlist' => '🎶', 'Floor-to-Ceiling Windows' => '🌅', 'Mirror Walls' => '🪞',
                            'Fresh Florals Weekly' => '🌼', 'Tripod-Friendly' => '📸'
                        ];
                    @endphp
                    @foreach($availableTags as $tagName => $icon)
                        <input class="tag-checkbox" type="checkbox" name="tags[]" value="{{ $tagName }}" id="tag-{{ $loop->index }}">
                        <label class="tag-pill" for="tag-{{ $loop->index }}"><span class="tag-icon">{{ $icon }}</span> {{ $tagName }}</label>
                    @endforeach
                </div>
            </div>
        </div>



        <!-- Section: Statistics -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon">📊</div>
                <div>
                    <div class="card-title">Shop Statistics</div>
                    <div class="card-subtitle">Promotional metrics (leave blank if unknown)</div>
                </div>
            </div>
            <div class="card-body">
                
                <!-- 1. The Typed Input -->
                <div class="field">
                    <label class="field-label">Google Rating</label>
                    <input type="text" name="stats[Google Rating]" placeholder="e.g. 4.8" style="max-width: 200px;">
                </div>

                <!-- 2. The Button Inputs -->
                <div class="field" style="margin-top: 1rem;">
                    <label class="field-label">Price Range</label>
                    <div class="tag-grid">
                        <input class="tag-checkbox" type="radio" name="stats[Price Range]" value="Below ₱100" id="price-1">
                        <label class="tag-pill" for="price-1">Below ₱100</label>

                        <input class="tag-checkbox" type="radio" name="stats[Price Range]" value="₱100 - ₱200" id="price-2">
                        <label class="tag-pill" for="price-2">₱100 - ₱200</label>

                        <input class="tag-checkbox" type="radio" name="stats[Price Range]" value="₱200+" id="price-3">
                        <label class="tag-pill" for="price-3">₱200+</label>
                    </div>
                </div>

                <div class="field" style="margin-top: 1rem;">
                    <label class="field-label">Noise Level</label>
                    <div class="tag-grid">
                        <input class="tag-checkbox" type="radio" name="stats[Noise Level]" value="Quiet" id="noise-1">
                        <label class="tag-pill" for="noise-1">🤫 Quiet</label>

                        <input class="tag-checkbox" type="radio" name="stats[Noise Level]" value="Moderate" id="noise-2">
                        <label class="tag-pill" for="noise-2">🗣️ Moderate</label>

                        <input class="tag-checkbox" type="radio" name="stats[Noise Level]" value="Loud" id="noise-3">
                        <label class="tag-pill" for="noise-3">🎶 Loud</label>
                    </div>
                </div>

                <div class="field" style="margin-top: 1rem;">
                    <label class="field-label">Wi-Fi Speed</label>
                    <div class="tag-grid">
                        <input class="tag-checkbox" type="radio" name="stats[Wi-Fi Speed]" value="Basic" id="wifi-1">
                        <label class="tag-pill" for="wifi-1">📶 Basic</label>

                        <input class="tag-checkbox" type="radio" name="stats[Wi-Fi Speed]" value="Fast" id="wifi-2">
                        <label class="tag-pill" for="wifi-2">⚡ Fast</label>

                        <input class="tag-checkbox" type="radio" name="stats[Wi-Fi Speed]" value="Ultra" id="wifi-3">
                        <label class="tag-pill" for="wifi-3">🚀 Ultra</label>
                    </div>
                </div>

            </div>
        </div>

            <!-- Section: Utility & Conversion -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon">📍</div>
                <div>
                    <div class="card-title">Utility & Conversion</div>
                    <div class="card-subtitle">Drives engagement and foot traffic</div>
                </div>
            </div>
            <div class="card-body">
                <div class="field">
                    <label class="field-label">Must Try Item</label>
                    <input type="text" name="must_try" placeholder="e.g. Spanish Latte & Brazo de Mercedes" required>
                    <span class="field-hint">Highlight your top recommendation</span>
                </div>
                <div class="field">
                    <label class="field-label">Google Maps URL</label>
                    <input type="url" name="maps_url" placeholder="https://maps.google.com/…" required>
                </div>
            </div>
        </div>

        <button class="btn-deploy" type="submit">Deploy to Database</button>

        <div style="margin-top: 1rem; text-align: center; font-size: 11px; color: var(--text-muted);">
            🔒 Protected route · CSRF validated · Aiven MySQL
        </div>
    </form>
</div>

        <div id="customDeleteModal" style="position: fixed; inset: 0; background: rgba(28,13,0,0.8); backdrop-filter: blur(5px); z-index: 2000; display: none; align-items: center; justify-content: center; padding: 1rem; opacity: 0; transition: opacity 0.3s ease;">
        <div style="background: var(--surface); width: 100%; max-width: 400px; padding: 2rem; border-radius: var(--radius-lg); box-shadow: 0 20px 60px rgba(0,0,0,0.3); text-align: center; transform: translateY(20px); transition: transform 0.3s ease;" id="deleteModalBox">
            
            <div style="width: 50px; height: 50px; background: #fee2e2; color: #991b1b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 1rem;">
                ⚠️
            </div>
            
            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; color: var(--espresso); margin-bottom: 0.5rem;">Confirm Deletion</h3>
            <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 2rem; line-height: 1.5;">
                Are you sure you want to permanently delete <strong id="deleteShopName" style="color: var(--espresso);"></strong>? This action cannot be undone and will destroy all associated statistics.
            </p>

            <form id="globalDeleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div style="display: flex; gap: 10px;">
                    <button type="button" onclick="closeDeleteModal()" style="flex: 1; padding: 12px; background: var(--surface-alt); color: var(--text-primary); border: 1px solid var(--border); border-radius: 100px; font-weight: 700; cursor: pointer; transition: 0.2s;">
                        Cancel
                    </button>
                    <button type="submit" style="flex: 1; padding: 12px; background: #dc2626; color: white; border: none; border-radius: 100px; font-weight: 700; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                        Yes, Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
    function previewImage(url) {
        const img = document.getElementById('preview-img');
        const hint = document.getElementById('img-preview-hint');
        if (url && url.startsWith('http')) {
            img.src = url;
            img.style.display = 'block';
            hint.style.display = 'none';
            img.onerror = () => { img.style.display = 'none'; hint.style.display = 'block'; };
        } else {
            img.style.display = 'none';
            hint.style.display = 'block';
        }
    }
        function openDeleteModal(id, name) {
            const modal = document.getElementById('customDeleteModal');
            const modalBox = document.getElementById('deleteModalBox');
            
            // 1. Inject the specific shop name into the warning text
            document.getElementById('deleteShopName').innerText = name;
            
            // 2. Dynamically change the form's destination URL so it deletes the correct ID
            document.getElementById('globalDeleteForm').action = '/admin/shops/' + id;
            
            // 3. Show the modal with a smooth fade/slide animation
            modal.style.display = 'flex';
            // Slight delay ensures the display flex registers before applying opacity for CSS transitions
            setTimeout(() => {
                modal.style.opacity = '1';
                modalBox.style.transform = 'translateY(0)';
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('customDeleteModal');
            const modalBox = document.getElementById('deleteModalBox');
            
            // Animate out
            modal.style.opacity = '0';
            modalBox.style.transform = 'translateY(20px)';
            
            // Wait for animation to finish before hiding completely
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
</script>
</body>
</html>