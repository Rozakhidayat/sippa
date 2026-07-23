<?php

namespace App\Models;

use App\Models\User;
use App\Models\Theme;
use App\Models\Historie;
use App\Models\Workflow;
use App\Models\Departement;
use App\Models\DevelopmentTask;
use App\Models\DevelopmentProgres;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\SubmissionNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model 
{
    
    protected $guarded = ['id'];
    protected $with = ['user', 'departement', 'themes', 'step'];
    
    protected $casts = [
    'submission_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(Historie::class);
    }

    public function businessPartner()
    {
        return $this->belongsTo(User::class, 'bp_id');
    }

    public function step()
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function themes()
    {
        return $this->belongsToMany(Theme::class, 'submission_themes');
    }

    public function develop_progres(): HasMany
    {
        return $this->hasMany(DevelopmentProgres::class);
    }

    public function getTypeDevelopmentLabelAttribute()
    {
        return match ($this->type_development) {
            'aplikasi_baru' => 'Aplikasi Baru',
            'peningkatan'   => 'Peningkatan',
            default         => ucwords(str_replace('_', ' ', $this->type_development)),
        };
    }

    public function getStepStatus(string $targetState): string
    {
        $currentStep = $this->step; 
        $targetStep = Workflow::query()
            ->where('state_code', $targetState)
            ->first();

        if (!$targetStep) return '';

        if ($this->status === 'disetujui') {
            return 'done';
        }

        // 1. TAMBAHAN KHUSUS: Jika status rejected atau revision, 
        // tandai step yang sedang aktif/berhenti saat ini dengan warna merah/kuning
        if ($this->workflow_id === $targetStep->id) {
            if ($this->status === 'rejected') {
                return 'active rejected';
            }
            if ($this->status === 'revision' || $this->status === 'perbaikan_brd') {
                return 'active revision'; 
            }
            return 'active'; 
        }
        
        // 2. Jika status bukan workflow_id tapi teks statusnya mengandung rejected/revision,
        // fallback ke step saat ini ($currentStep)
        if ($currentStep && $currentStep->id === $targetStep->id) {
            if ($this->status === 'rejected') {
                return 'active rejected';
            }
            if ($this->status === 'revision' || $this->status === 'perbaikan_brd') {
                return 'active revision'; 
            }
        }

        if ($currentStep && $currentStep->sort_order > $targetStep->sort_order) {
            return 'done';
        }
        
        $isLastStep = ! Workflow::query()
            ->where('sort_order', '>', $currentStep?->sort_order ?? 0)
            ->exists();

        if ($isLastStep && $this->status === $targetState) {
            return 'done';
        }
        
        return '';
    }
    // public function getStepStatus(string $targetState): string
    // {
    //     $currentStep = $this->step; 
    //     $targetStep = Workflow::query()
    //         ->where('state_code', $targetState)
    //         ->first();

    //     if (!$targetStep) return '';

    //     if ($this->status === 'disetujui') {
    //         return 'done';
    //     }
        
    //     if ($this->workflow_id === $targetStep->id) {
    //         if (str_contains($this->status, 'rejected')) return 'active rejected';
    //         if (str_contains($this->status, 'revision') || $this->status === 'perbaikan_brd') {
    //             return 'active revision'; 
    //         }
    //         return 'active'; 
    //     }

    //     if ($currentStep && $currentStep->sort_order > $targetStep->sort_order) {
    //         return 'done';
    //     }
        
    //     $isLastStep = ! Workflow::query()
    //         ->where('sort_order', '>', $currentStep?->sort_order ?? 0)
    //         ->exists();

    //     if ($isLastStep && $this->status === $targetState) {
    //         return 'done';
    //     }
    //     return '';
    // }
    
    public function getStatusColorAttribute()
    {
        $status = strtolower($this->status);

        return match (true) {
            str_contains($status, 'rejected')      => 'danger', 
            str_contains($status, 'revision')      => 'warning',
            str_contains($status, 'perbaikan_brd') => 'warning',
            str_contains($status, 'selesai') => 'success',

            $this->step ? true : false => $this->step?->color ?? 'primary',

            default => 'primary',
        };
    }
    public function getStatusLabelAttribute()
    {
        $labelMap = [
            'revision'      => 'Revisi',
            'perbaikan_brd' => 'Perbaikan BRD',
            'rejected'      => 'Ditolak',
        ];

        if (array_key_exists($this->status, $labelMap)) {
            return $labelMap[$this->status];
        }

        if ($this->step) {
            return $this->step->label;
        }

        return ucwords(str_replace('_', ' ', $this->status));
    }

    public function createDevelopmentProgres()
    {
        $tasks = DevelopmentTask::query()
        ->where('type_development', $this->type_development)
        ->orderBy('sort_order', 'asc')
        ->get();
        
        // $tasks = DevelopmentTask::where('type_development', $this->type_development)
        //         ->orderBy('sort_order', 'asc')
        //         ->get();
        
        foreach ($tasks as $index => $task) {
            $this->develop_progres()->create([
                'develop_task_id' => $task->id,
                'status' => ($index === 0) ? 'proses' : 'menunggu',
            ]);
        }
    }


    public function processAction(string $actionType, string $lastRemark)
    {
        DB::transaction(function () use ($actionType, $lastRemark) {
            $user = Auth::user();
            $roleName = $user->role->name; 
            $currentStep = Workflow::query()->find($this->workflow_id);

            $this->previous_state = $this->status;

            if ($actionType === 'setuju') {
                $nextStep = Workflow::query()
                    ->where('sort_order', '>', $currentStep?->sort_order ?? 0)
                    ->where('is_active', true)
                    ->orderBy('sort_order', 'asc')
                    ->first();

                if ($nextStep && $nextStep->state_code !== 'disetujui') {
                    $this->status = $nextStep->state_code;
                    $this->workflow_id = $nextStep->id;
                    $this->previous_state = null;
                    $this->save();

                    if ($nextStep->role_id) {
                    $role = \App\Models\Role::find($nextStep->role_id);
                    $roleName = $role ? $role->name : '';

                    $query = User::query()->where('role_id', $nextStep->role_id);
                    
                    if ($roleName === 'SVP') {
                        $query->where('departement_id', $this->departement_id);
                    }

                    $targets = $query->get();
                    foreach ($targets as $target) {
                        $target->notify(new SubmissionNotification($this, "Menunggu persetujuan Anda"));
                    }
                }

                } else {
                    $this->status = 'disetujui';
                    $this->workflow_id = $nextStep ? $nextStep->id : null; 
                    $this->previous_state = null;
                    $this->save();


                    if ($this->develop_progres()->count() === 0) {
                        $this->createDevelopmentProgres();
                    }
                    
                    $developers = User::whereHas('role', function($query) {
                        $query->where('name', 'Developer'); 
                    })->get();

                    foreach ($developers as $developer) {
                        $developer->notify(new SubmissionNotification(
                            $this, 
                            "Aplikasi '{$this->application_name}' telah disetujui dan siap dikembangkan."
                        ));
                    }

                    if ($nextStep && $nextStep->role_id) {
                        $admins = User::query()
                            ->where('role_id', $nextStep->role_id)
                            ->get();
                        foreach ($admins as $admin) {
                            $admin->notify(new SubmissionNotification($this, "Pengajuan"));
                        }
                    }

                    $this->user->notify(new SubmissionNotification($this, "Pengajuan disetujui sepenuhnya!"));
                }

            } elseif ($actionType === 'revisi') {
                $backToBP = ['Manajer TI', 'Enterprise Architect'];
                
                if (in_array($roleName, $backToBP)) {
                    $this->status = 'perbaikan_brd';
                    if ($this->bp_id) {
                        $this->businessPartner?->notify(new SubmissionNotification($this, "Perlu perbaikan BRD"));
                    }
                } else {
                    $this->status = 'revision';
                    $this->user->notify(new SubmissionNotification($this, "Pengajuan diminta untuk direvisi"));
                }
            } elseif ($actionType === 'tolak') {
                $this->status = 'rejected';
                $this->user->notify(new SubmissionNotification($this, "Pengajuan Anda ditolak"));
            }

            $this->last_remark = $lastRemark;
            $this->save();

            $infoLabel = [
                'setuju' => 'disetujui',
                'revisi' => 'diminta perbaikan',
                'tolak'  => 'ditolak'
            ];
            $statusInfo = $infoLabel[$actionType] ?? $actionType;

            $this->histories()->create([
                'user_id'     => $user->id,
                'action'      => strtoupper($actionType),
                'information' => "Pengajuan telah $statusInfo |$lastRemark"
            ]);
        });
    }
}
