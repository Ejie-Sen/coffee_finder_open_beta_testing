<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get the requested state from the URL (defaulting to 'work')
        $currentMood = $request->get('mood', 'work');
        $searchQuery = $request->get('search', '');

        // 2. Start the database query with Eager Loading
        //$query = Shop::with(['menuItems', 'stats', 'vibes']); | perfomance fix! -ejie
        $query = Shop::with(['vibes', 'stats']);

        // 3. Apply the filters (Mimicking your App.jsx logic)
        if (!empty($searchQuery)) {
            $query->where('name', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('tagline', 'LIKE', "%{$searchQuery}%");
        } else {
            $query->where('mood', $currentMood);
        }

        // 4. Execute the query
        $shops = $query->get();

        // 5. Send data to the view
        return view('welcome', compact('shops', 'currentMood', 'searchQuery'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'mood' => 'required|string|max:50',
            'badge' => 'required|string|max:100',
            'emoji' => 'required|string|max:10',
            'image_url' => 'nullable|url|max:1000',
            'tagline' => 'required|string|max:255',
            'must_try' => 'required|string|max:255',
            'maps_url' => 'required|url|max:2048',
            'tags' => 'array'
        ]);

        // Convert the array of tags into a JSON string for the database
        // 1. Create the Parent (The Shop)
        // 1. Create the Parent (The Shop)
        $shop = Shop::create($validated);

        // 2. Create the Children (The Vibes)
        if ($request->has('tags')) {
            foreach ($request->input('tags') as $vibeName) {
                $shop->vibes()->create([
                    'vibe' => $vibeName
                ]);
            }       
        }

        // 3. Create the Children (The Stats)
        if ($request->has('stats')) {
            foreach ($request->input('stats') as $label => $value) {
                // Only save to the database if the admin actually selected a value
                if (!empty($value)) {
                    $shop->stats()->create([
                        'label' => $label,
                        'stat_value' => $value
                    ]);
                }
            }
        }

        return redirect('/admin')->with('success', 'Shop deployed to database successfully.');
    }
}