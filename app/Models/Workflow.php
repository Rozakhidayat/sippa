<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Workflow extends Model
{
    protected $fillable = [
        'label',
        'state_code',
        'sort_order',
        'role_id',
        'color'
    ];

    protected $with = ['role'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    protected static function booted()
    {
        static::saving(function ($step) {
            if ($step->isDirty('sort_order')) {
                $newOrder = $step->sort_order;
                $oldOrder = $step->getOriginal('sort_order');

                static::withoutEvents(function () use ($step, $newOrder, $oldOrder) {
                    if (is_null($oldOrder)) {
                        static::query()
                            ->where('sort_order', '>=', $newOrder)
                            ->increment('sort_order');
                    } else {
                        if ($newOrder < $oldOrder) {
                            static::query()
                                ->where('sort_order', '>=', $newOrder)
                                ->where('sort_order', '<', $oldOrder)
                                ->where('id', '!=', $step->id)
                                ->increment('sort_order');
                        } elseif ($newOrder > $oldOrder) {
                            static::query()
                                ->where('sort_order', '<=', $newOrder)
                                ->where('sort_order', '>', $oldOrder)
                                ->where('id', '!=', $step->id)
                                ->decrement('sort_order');  
                        }
                    }
                });
            }
        });
    }
}
