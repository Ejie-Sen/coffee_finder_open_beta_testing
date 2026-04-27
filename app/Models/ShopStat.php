<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopStat extends Model
{
    // 1. Disable timestamps to prevent crashes
    public $timestamps = false;

    // 2. Allow mass assignment for your columns
    protected $guarded = [];
    
    // 3. Ensure the JSON 'tags' column is cast to a PHP array automatically
    protected $casts = [
        'tags' => 'array',
    ];
}
