<?php

namespace App\Models;

use App\Models\Submission;
use App\Models\DevelopmentTask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevelopmentProgres extends Model
{
    protected $with = ['submission', 'development_task'];

    protected $guarded = ['id'];

    protected $casts = [
        'completed_at' => 'datetime', 
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function development_task(): BelongsTo
    {
        return $this->belongsTo(DevelopmentTask::class, 'develop_task_id');
    }
}
