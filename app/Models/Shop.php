<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    // 1. Disable timestamps to prevent crashes
    public $timestamps = false;

    // 2. Allow mass assignment for your columns
    // protected $guarded = [];  | SQL injection risk fix! -ejie
    protected $fillable = ['name', 'mood', 'badge', 'emoji', 'image_url', 'tagline', 'must_try', 'maps_url', 'tags'];
    // 3. Ensure the JSON 'tags' column is cast to a PHP array automatically
    protected $casts = [
        'tags' => 'array',
    ];

    public function stats()
    {
        return $this->hasMany(ShopStat::class);
    }

    public function vibes()
    {
        return $this->hasMany(ShopVibe::class);
    }
}
