<?php

namespace App\Models;

use App\Models\Direktorat;
use App\Models\Departement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kompartement extends Model
{
    protected $guarded = ['id'];
    protected $with = ['direktorat'];
    
    public function direktorat(): BelongsTo
    {
        return $this->belongsTo(Direktorat::class);
    }

    public function departements(): HasMany
    {
        return $this->hasMany(Departement::class);
    }
}
