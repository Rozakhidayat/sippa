<?php

use Livewire\Component;
use App\Models\Workflow;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        $svpStateCode = Workflow::whereHas('role', fn($q) => $q->where('name', 'SVP'))
                    ->first()?->state_code ?? 'review_s_v_p';
        
       /** @var \Illuminate\Database\Eloquent\Builder<\App\Models\Submission> $query */
        $query = Submission::query();

        $pendingSvp = $query
            ->where('departement_id', $user->departement_id)
            ->where('status', $svpStateCode)
            ->count();
        
        
        $status = [
            'pending_svp' => $pendingSvp
        ];

        return $this->view([
            'status' => $status
        ]);
    }
};
?>

<div>
<main id="main" class="main">
        <div class="pagetitle">
            <div class="fw-bold text-primary">Selamat Datang {{ Auth::user()->name }}</div>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><span>Home</span></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
    
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row g-3">
    
                        <div class="col-lg-5 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Menunggu Persetujuan</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-clock-history text-warning"></i>
                                        </div>
                                        <div class="ps-3">
                                            <a href='/svp/approval' wire:navigate class="fw-semibold fs-3"
                                                style="color:#012970">{{ $status['pending_svp'] }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>