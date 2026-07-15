<?php

use Livewire\Component;
use App\Models\Workflow;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public $search = '';
    
    public function render()
    {
        
        $user = Auth::user();
        
        $allowedWorkflowStates = Workflow::query()
            ->where('role_id', $user->role_id)
            ->where('is_active', true)
            ->pluck('state_code');
        
     /** @var \Illuminate\Database\Eloquent\Builder<\App\Models\Submission> $query */
        $query = Submission::query();   
        $submissions = $query
            ->whereIn('status', $allowedWorkflowStates)
            ->where('departement_id', $user->departement_id)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('application_name', 'like', '%' . $this->search . '%')
                        ->orWhere('no_ticket', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get();
    
        return $this->view([
            'submissions' => $submissions
        ]);
    }
};
?>

<div>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Pengajuan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/svp/dashboard" wire:navigate>Dashboard</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">Pengajuan</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="container-fluid p-0">
                <div class="card border-0 shadow-sm">
                    <div class="card-body px-2">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2 py-3">
                            <div class="d-flex gap-2 flex-grow-1" style="max-width: 800px;">
                                <div class="position-relative" style="max-width: 300px;">
                                    <i
                                        class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                    <input type="text" wire:model.live.debounce.500ms="search" class="form-control ps-5"
                                        placeholder="Cari nama aplikasi...">
                                </div>
                                {{-- <select class="form-select" style="width: 200px;">
                                    <option selected>Semua Status</option>
                                </select>
                                <button class="btn btn-success px-4"><i class="bi bi-search me-1"></i> Filter</button>
                                --}}
                            </div>
                        </div>

                        <div class="table-responsive" style="overflow: visible;">
                            <table class="table table-hover align-middle mb-0"
                                style="width: 100%; table-layout: auto; min-width: 100%;">
                                <thead class="text-muted text-uppercase"
                                    style="font-size: 11px; background-color: #f8f9fa;">
                                    <tr>
                                        <th class="py-3 ps-3" style="width: 155px;">No. Tiket</th>
                                        <th class="py-3">Nama Aplikasi</th>
                                        <th class="py-3">Pemohon</th>
                                        <th class="py-3">Unit Kerja</th>
                                        <th class="py-3">Jenis</th>
                                        <th class="py-3 text-center">Status</th>
                                        <th class="py-3">Tanggal</th>
                                        <th class="py-3 text-center pe-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 13.5px;">
                                    @forelse ($submissions as $submission)
                                    <tr class="border-bottom">
                                        <td class="ps-3">
                                            <span
                                                class="badge bg-danger-subtle text-danger border border-danger-subtle fw-normal px-2">
                                                {{ $submission->no_ticket }}
                                            </span>
                                        </td>
                                        <td class="fw-bold">{{ $submission->application_name}}</td>
                                        <td class="text-nowrap">{{ $submission->user->name }}</td>
                                        <td>
                                            <div style="line-height: 1.2;">
                                                <div>{{ $submission->departement->name }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-success px-2 py-1" style="font-weight: 500;">{{
                                                $submission->type_development_label}}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $submission->status_color }} px-2 py-1">{{
                                                $submission->status_label }}</span>
                                        </td>
                                        <td class="text-nowrap text-muted">{{ $submission->submission_date->format('d F
                                            Y')
                                            }}</td>
                                        <td class="text-center pe-3">
                                            <a href="{{ route('svp.approval.show', $submission->no_ticket) }}"
                                                wire:navigate
                                                class="btn btn-outline-primary d-inline-flex align-items-center gap-1 py-1 px-3"
                                                style="font-size: 12px; border-radius: 6px; border-width: 1.5px; font-weight: 500;">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            Tidak ada data
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <x-alert />
</div>