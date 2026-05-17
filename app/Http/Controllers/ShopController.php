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

    public function adminIndex()
    {
        // Fetch all shops from the database, newest first
        $shops = Shop::orderBy('id', 'desc')->get();
        
        // Pass them to the admin view
        return view('admin', compact('shops'));
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

    public function destroy($id)
    {
        // 1. Find the specific shop by its ID, or fail safely if it doesn't exist
        $shop = Shop::findOrFail($id);
        
        // 2. Delete it from the database
        $shop->delete();

        // 3. Redirect back to the admin dashboard with a success message
        return redirect()->route('admin.index')->with('success', "{$shop->name} has been permanently deleted.");
    }

    public function edit($id)
    {
        // 1. Find the specific shop AND load its connected tables (vibes and stats)
        $shop = Shop::with(['vibes', 'stats'])->findOrFail($id);
        
        // 2. We need to extract just the tag names into a simple array so our checkboxes know which ones to check
        $activeTags = $shop->vibes->pluck('tag_name')->toArray();
        
        // 3. Send the data to a new 'edit' view
        return view('edit', compact('shop', 'activeTags'));
    }

    public function update(Request $request, $id)
    {
        // 1. Find the specific shop being edited
        $shop = Shop::findOrFail($id);

        // 2. Validate the incoming data (same rules as when creating)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'mood' => 'required|in:work,eat,chill',
            'badge' => 'required|string|max:50',
            'emoji' => 'required|string|max:10',
            'tagline' => 'required|string|max:255',
            'image_url' => 'required|url',
            'must_try' => 'required|string|max:255',
            'maps_url' => 'required|url|max:255',
        ]);

        // 3. Update the core shop details
        $shop->update($validatedData);

        // 4. Update the Amenities (Tags)
        // First, we wipe the old tags clean to prevent duplicates
        $shop->vibes()->delete(); 
        
        // Then, if they checked any boxes, we insert the new ones
        if ($request->has('tags')) {
            foreach ($request->tags as $tag) {
                $shop->vibes()->create(['tag_name' => $tag]);
            }
        }

        // 5. Kick them back to the admin table with a success message
        return redirect()->route('admin.index')->with('success', "{$shop->name} has been successfully updated.");
    }
}