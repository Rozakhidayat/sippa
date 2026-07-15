<?php

use Livewire\Component;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public function render()
    {
        $user = Auth::user()->id;
        
        /** @var \Illuminate\Database\Eloquent\Builder<\App\Models\Submission> $query */
        $query = Submission::where('user_id', $user);

        $total = $query->count();

        $completed = Submission::where('user_id', $user)
            ->whereHas('develop_progres')
            ->whereDoesntHave('develop_progres', function($q) {
                $q->where('status', '!=', 'selesai');
            })->count();

            
        $rejected = Submission::where('user_id', $user)
            ->where('status', 'rejected')
            ->count();
    
        $pending = $total - ($completed + $rejected);

        $status = [
            'total'     => $total,
            'completed' => $completed,
            'rejected'  => $rejected,
            'pending'   => $pending
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

                        <div class="col-lg-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Pengajuan Saya</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div class="ps-3">
                                            <a class="fw-semibold fs-3" style="color:#012970" href='/pengajuan'
                                                wire:navigate>{{
                                                $status['total'] }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Sedang Diproses</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-clock-history text-warning"></i>
                                        </div>
                                        <div class="ps-3">
                                            <a class="fw-semibold fs-3" style="color:#012970" href='/pengajuan'
                                                wire:navigate>{{
                                                $status['pending'] }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Selesai</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"
                                            style="background: #e0f8e9">
                                            <i class="bi bi-check-circle text-success"></i>
                                        </div>
                                        <div class="ps-3">
                                            <a class="fw-semibold fs-3" style="color:#012970" href='/pengajuan'
                                                wire:navigate>{{
                                                $status['completed'] }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Ditolak</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-x-circle text-danger"></i>
                                        </div>
                                        <div class="ps-3">
                                            <a class="fw-semibold fs-3" style="color:#012970" href='/pengajuan'
                                                wire:navigate>{{ $status['rejected'] }}</a>
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