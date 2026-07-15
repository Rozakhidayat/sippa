<?php

namespace App\Models;

use App\Models\themeCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Theme extends Model
{
    protected $with = ['category'];

    protected $fillable = [
        'category_id', 
        'item', 
        'periode', 
        'is_active', 
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(themeCategory::class, 'category_id');
    }
    
}
