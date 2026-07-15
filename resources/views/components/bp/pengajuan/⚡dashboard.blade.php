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
        
        $bpStateCode = Workflow::whereHas('role', fn($q) => $q->where('name', 'Business Partner'))
                        ->first()?->state_code ?? 'analisis_b_p';


         /** @var \Illuminate\Database\Eloquent\Builder<\App\Models\Submission> $analisis_query */
        $analisis_query = Submission::query();
        $analisis_bp = $analisis_query
                ->where('bp_id', $user->id)
                ->where('status', $bpStateCode)    
                ->count();
        
          /** @var \Illuminate\Database\Eloquent\Builder<\App\Models\Submission> $revisi_query */
        $revisi_query = Submission::query();
        $perbaikanBrd = $revisi_query
                ->where('bp_id', $user->id)
                ->where('status', 'perbaikan_brd')    
                ->count();
        
        $status = [
            'analisis_bp'    => $analisis_bp,
            'perbaikan_brd' => $perbaikanBrd,
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
                                    <h5 class="card-title">Menunggu Analysis</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-clock-history text-warning"></i>
                                        </div>
                                        <div class="ps-3">
                                            <a href='/bp/pengajuan' wire:navigate class="fw-semibold fs-3"
                                                style="color:#012970">{{ $status['analisis_bp'] }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Revisi</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-file-earmark-arrow-down"></i>
                                        </div>
                                        <div class="ps-3">
                                            <a href='/bp/pengajuan' wire:navigate class="fw-semibold fs-3"
                                                style="color:#012970">{{
                                                $status['perbaikan_brd'] }}</a>
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