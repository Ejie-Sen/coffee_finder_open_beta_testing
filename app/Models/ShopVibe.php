<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopVibe extends Model
{
    // 1. Disable timestamps to prevent crashes
    public $timestamps = false;

    // 2. Allow mass assignment for your columns
    // protected $guarded = [];  | SQL injection risk fix! -ejie
    protected $fillable = ['shop_id','vibe'];
    
    // 3. Ensure the JSON 'tags' column is cast to a PHP array automatically
    protected $casts = [
        'tags' => 'array',
    ];
}
