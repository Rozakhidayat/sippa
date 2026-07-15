<?php

namespace App\Models;

use App\Models\Role;
use App\Models\DevelopmentProgres;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevelopmentTask extends Model
{
    protected $with = ['role'];

    protected $fillable = [
        'type_development',
        'task_name',
        'sort_order',
        'role_id'
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function develop_progres(): HasMany
    {
        return $this->hasMany(DevelopmentProgres::class, 'develop_task_id');
    }

    function getTypeDevelopmentLabelAttribute()
    {
        return match ($this->type_development) {
            'aplikasi_baru' => 'Aplikasi Baru',
            'peningkatan' => 'Peningkatan',
        };
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
