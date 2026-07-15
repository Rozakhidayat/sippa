<?php

namespace App\Models;

use App\Models\Kompartement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Direktorat extends Model
{
    protected $fillable = ['name'];

    public function kompartements(): HasMany
    {
        return $this->hasMany(Kompartement::class);
    }
}
