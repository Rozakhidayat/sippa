<?php

namespace App\Models;

use App\Models\User;
use App\Models\Workflow;
use App\Models\DevelopmentTask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    
    public function workflows(): HasMany
    {
        return $this->hasMany(Workflow::class);
    }

    public function development_task(): HasMany
    {
        return $this->hasMany(DevelopmentTask::class);
    }
}
