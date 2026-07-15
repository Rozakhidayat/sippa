<?php

namespace App\Models;

use App\Models\Theme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class themeCategory extends Model
{
    protected $fillable = ['name'];

    public function themes(): HasMany
    {
        return $this->hasMany(Theme::class);
    }
}
