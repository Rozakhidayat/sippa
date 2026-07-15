<?php

namespace App\Models;

use App\Models\User;
use App\Models\Submission;
use App\Models\Kompartement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Departement extends Model
{
    protected $guarded = ['id'];
    protected $with = ['kompartement'];

    public function kompartement(): BelongsTo
    {
        return $this->belongsTo(Kompartement::class);
    }
    
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
