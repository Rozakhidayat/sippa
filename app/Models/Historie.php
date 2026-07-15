<?php

namespace App\Models;

use App\Models\User;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Historie extends Model
{
    protected $guarded = ['id'];

    protected $with = ['user', 'submission'];
    
    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
