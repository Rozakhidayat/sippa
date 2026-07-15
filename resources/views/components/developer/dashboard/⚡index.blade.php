<?php

use Livewire\Component;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public function render()
    {

        $completed = Submission::whereHas('develop_progres')
        ->whereDoesntHave('develop_progres', function($q) {
            $q->where('status', '!=', 'selesai');
        })->count();

        $pendingDevelop = Submission::whereHas('develop_progres', function($q) {
            $q->where('status', '!=', 'selesai');
        })->count();

        $status = [
            'pendingDevelop'    => $pendingDevelop,
            'complated' => $completed,
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
                                    <h5 class="card-title">Menunggu Proses Pengembangan</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-clock-history text-warning"></i>
                                        </div>
                                        <div class="ps-3">
                                            <a href='/developer/pengajuan' wire:navigate class="fw-semibold fs-3"
                                                style="color:#012970">{{ $status['pendingDevelop'] }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Selesai Pengembangan</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-check-circle text-success"></i>
                                        </div>
                                        <div class="ps-3">
                                            <a href='/developer/pengajuan' wire:navigate class="fw-semibold fs-3"
                                                style="color:#012970">{{
                                                $status['complated'] }}</a>
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