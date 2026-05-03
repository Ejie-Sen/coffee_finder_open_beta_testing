@extends('layouts.app')

@section('content')

    <section class="mood-section" id="mood">
        <p class="section-label">Step 1 — Choose your mood</p>
        <h2 class="mood-heading">What brings you<br/><em>out today?</em></h2>
        
        <div class="mood-buttons">
            <a href="/?mood=work" onclick="fetchSpots(event, this, '/?mood=work')" class="mood-btn work {{ $currentMood === 'work' && empty($searchQuery) ? 'active' : '' }}" style="text-decoration:none;">
                <span class="mood-icon">💻</span>
                <div class="mood-btn-title">Focus & Productivity</div>
                <div class="mood-btn-desc">You need fast Wi-Fi, a quiet corner, and enough outlets.</div>
            </a>
            <a href="/?mood=eat" onclick="fetchSpots(event, this, '/?mood=eat')" class="mood-btn eat {{ $currentMood === 'eat' && empty($searchQuery) ? 'active' : '' }}" style="text-decoration:none;">
                <span class="mood-icon">🍰</span>
                <div class="mood-btn-title">Cravings & Desserts</div>
                <div class="mood-btn-desc">You came for the cakes and the frappes — in that order.</div>
            </a>
            <a href="/?mood=chill" onclick="fetchSpots(event, this, '/?mood=chill')" class="mood-btn chill {{ $currentMood === 'chill' && empty($searchQuery) ? 'active' : '' }}" style="text-decoration:none;">
                <span class="mood-icon">✨</span>
                <div class="mood-btn-title">Aesthetic & Social</div>
                <div class="mood-btn-desc">You want a backdrop that makes you look like a film.</div>
            </a>
        </div>
    </section>

    <section class="results-section" id="results">
        <div class="results-header">
            <h2 class="results-title">
                @if(!empty($searchQuery))
                    Search Results for <span>"{{ $searchQuery }}"</span>
                @else
                    @php
                        $moodTitles = [
                            'work' => 'Focus & Productivity',
                            'eat' => 'Cravings & Desserts',
                            'chill' => 'Aesthetic & Social'
                        ];
                        $displayTitle = $moodTitles[$currentMood] ?? ucfirst($currentMood);
                    @endphp
                    Spots for <span>{{ $displayTitle }}</span>
                @endif
            </h2>
            <span class="results-count">Showing {{ $shops->count() }} spots</span>
        </div>

        @if($shops->isEmpty())
            <div class="empty-state">
                <span style="font-size: 3rem">🔍</span>
                <p>No spots matched. Try a different search!</p>
            </div>
        @else
            <div class="cards-grid">
                @foreach($shops as $shop)
                    <div class="spot-card" data-shop="{{ $shop->toJson() }}" onclick="openModal(this)">
                        @if($shop->image_url)
                            <img src="{{ $shop->image_url }}" alt="{{ $shop->name }}" class="card-image" />
                        @else
                            <div class="card-img-placeholder" style="background: {{ $shop->bg_gradient ?? '#333' }}">
                                {{ $shop->emoji ?? '📍' }}
                            </div>
                        @endif

                        <div class="card-body">
                            <span class="card-badge">⭐ {{ $shop->badge ?? 'New Spot' }}</span>
                            <h3 class="card-name">{{ $shop->name }}</h3>
                            <p class="card-tagline">{{ $shop->tagline }}</p>
                            
                            <div class="card-must-try">
                                <strong>✦ Must Try</strong><br/>
                                {{ $shop->must_try ?? 'Ask the barista!' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <div id="shopModal" class="modal-overlay" onclick="closeModal()">
        <div class="modal" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="closeModal()">×</button>
            
            <div id="modalHero" class="modal-hero"></div>
            
            <div class="modal-body">
                <p class="modal-badge" id="modalBadge"></p>
                <h2 class="modal-name" id="modalName"></h2>
                <p class="modal-tagline"><em id="modalTagline"></em></p>

                <hr style="margin: 1.5rem 0; opacity: 0.1" />
                <div class="modal-section">
                    <h4 class="modal-section-title">✨ Amenities & Features</h4>
                    <div class="modal-vibes" id="modalVibes">
                        </div>
                </div>

                <div class="modal-actions" style="margin-top: 2rem;">
                    <button class="modal-btn modal-btn-secondary" onclick="closeModal()">← Back to Results</button>
                    <a id="modalMaps" href="#" target="_blank" class="modal-btn modal-btn-primary" style="text-align: center; text-decoration: none;">📍 Get Directions</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>

    //close modal function
    // fix para sa onlick="closeModal()" tangina -ejie
    function closeModal() {
        document.getElementById('shopModal').classList.remove('open');
    }

    //handle search form submission
    function handleSearch(event) {
    event.preventDefault();
    const query = document.querySelector('.search-input').value;
    fetchSpots(event, null, `/?search=${encodeURIComponent(query)}`);
    return false;
    }

    //FETCH SPOTS VIA AJAX
    async function fetchSpots(event, clickedElement, url) {
    // 1. Stop the hard browser reload
    event.preventDefault();

    // 2. Visually update the active button immediately
    if (clickedElement){
    document.querySelectorAll('.mood-btn').forEach(btn => btn.classList.remove('active'));
    clickedElement.classList.add('active');
    }
    // added the Auto-Scroll on Mood Selection -ejie
    document.getElementById('results').scrollIntoView({ 
    behavior: 'smooth', 
    block: 'start' 
    });


    const resultsSection = document.getElementById('results');
    
    // 3. Add a slight fade effect to indicate loading
    resultsSection.style.transition = 'opacity 0.2s ease-out';
    resultsSection.style.opacity = '0.4';

    try {
        // 4. Fetch the new HTML from the Laravel server asynchronously
        const response = await fetch(url, {
            headers: {
             'X-Requested-With': 'XMLHttpRequest',
             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
             }
        });
        
        const html = await response.text();

        // 5. Parse the returned HTML into a virtual DOM
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // 6. Extract the new grid and swap it into the live page
        const newResults = doc.getElementById('results').innerHTML;
        resultsSection.innerHTML = newResults;

        // 7. Update the browser URL quietly
        window.history.pushState({}, '', url);

        // 8. Fade it back in
        resultsSection.style.opacity = '1';
        
        // 9. Auto-scroll to the results seamlessly
        resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });

    } //catch (error) {
    // console.error('Fetch failed, falling back to hard reload:', error);
    // window.location.href = url; // Fallback to standard navigation if JS fails
    //}}
    catch (error) {
    console.error('Fetch failed:', error);
    alert('⚠️ Failed to load spots. Refreshing page...');
    window.location.href = url;
}// better error handling with user feedback -ej
    }
// Handle the browser Back/Forward buttons gracefully
    //window.addEventListener('popstate', () => {
    //window.location.reload(); }); This will destroy the SPA experience -ejie  
    window.addEventListener('popstate', (event) => {
    if(event.state && event.state.url) {
        fetchSpots(new Event('popstate'), null, event.state.url);
    } else {
        window.location.reload();
    }
    });// This will attempt to fetch the previous state via AJAX, but if it fails (e.g. user directly navigates to a URL), it will do a full reload. -ejie

// Properly initialize the first page state using the real window location
window.history.replaceState({ url: window.location.pathname + window.location.search }, '', window.location.href);

    const tagIcons = {
    'Fast WiFi': '⚡', 'Air Conditioning': '❄️', 'Outdoor Seating': '🍃', 
    'Pet Friendly': '🐾', 'Power Outlets': '🔌', 'Study Tables': '📚', 'Parking': '🚗',
    'Signature Cakes': '🍰', 'Milktea': '🧋', 'Family-Friendly': '👨‍👩‍👧‍👦', 'Aircon': '❄️',
    'Pastry Fresh Daily': '🍰', 'Coffee & Tea': '☕', 'Takeout Boxes': '🎁', 
    'Date Spot': '💕', 'Floral Pastries': '🌸', 'Seasonal Flavors': '🍓', 'Gift Boxes': '🎁',
    'Quiet Vibe': '🤫', 'Specialty Coffee': '☕', 'Wi-Fi Ready': '📶', 'Charging Points': '🔋', 
    'Dim Lighting': '🕯️', 'Ultra-Fast Wi-Fi': '🚀', 'Standing Desk Area': '🧍', 
    'Enforced Quiet Zone': '🤫', 'Printer Available': '🖨️', 'Fresh-Baked Daily': '🥐', 
    'Milk Pairings': '🥛', 'Homey Atmosphere': '🏡', 'Cupcakes Too': '🧁',
    'Instagrammable': '📸', 'Plush Seating': '🛋️', 'Botanical Decor': '🌿', 'Natural Light': '☀️',
    'Dark Aesthetic': '🖤', 'Candlelit Corners': '🕯️', 'Curated Playlist': '🎵', 'Photo Walls': '🖼️',
    'Homey Interiors': '🏡', 'Bean Bags': '🛋️', 'Warm Lighting': '💡', 'Chill Playlist': '🎶',
    'Floor-to-Ceiling Windows': '🪟', 'Mirror Walls': '🪞', 'Fresh Florals Weekly': '💐', 'Tripod-Friendly': '📸'
};

function openModal(element) {
    // 1. Safely extract and parse the data from the clicked card
    const shop = JSON.parse(element.getAttribute('data-shop'));

    // Populate Core Data
    document.getElementById('modalName').innerText = shop.name;
    document.getElementById('modalBadge').innerText = '⭐ ' + (shop.badge || 'New Spot');
    document.getElementById('modalTagline').innerText = shop.tagline;
    document.getElementById('modalMaps').href = shop.maps_url || shop.mapsUrl || '#';

    // Handle Image vs Emoji
    const hero = document.getElementById('modalHero');

    if(shop.image_url) {
        hero.style.padding = '0';
        hero.innerHTML = `<img src="${shop.image_url}" style="width:100%; height:100%; object-fit:cover;">`;
    } else {
        hero.style.background = shop.bg_gradient || shop.bgGradient || '#2b1b12';
        hero.innerHTML = `<span style="font-size:4rem">${shop.emoji}</span>`;
    }

    // Parse and Inject Amenities
    const vibesContainer = document.getElementById('modalVibes');
    vibesContainer.innerHTML = ''; // Clear old tags
    
    let parsedTags = [];
    try {
        if (Array.isArray(shop.tags)) parsedTags = shop.tags;
        else if (typeof shop.tags === 'string') parsedTags = JSON.parse(shop.tags);
    } catch(e) { console.error("Tag parse error"); }

    // Fallback to vibes table if tags column is empty
    if(parsedTags.length === 0 && shop.vibes) {
        parsedTags = shop.vibes.map(v => v.vibe.replace(/^[^\w\s]+/, '').trim()); 
    }

    if(parsedTags.length > 0) {
        parsedTags.forEach(tag => {
            let icon = tagIcons[tag] || '✨';
            vibesContainer.innerHTML += `<span class="vibe-tag" style="background: #f4f1ee">${icon} ${tag}</span>`;
        });
    } else {
        vibesContainer.innerHTML = '<p style="font-size: 0.85rem; opacity: 0.6; font-style: italic;">No amenities listed for this spot yet.</p>';
    }

    // Show Modal
    document.getElementById('shopModal').classList.add('open');
}
</script>
@endpush