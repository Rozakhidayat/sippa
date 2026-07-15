<?php

use Livewire\Component;
use App\Models\Submission;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    use WithPagination;
    
    public $search = '';

    #[On('createdSubmission')]
    #[On('updatedSubmission')]
    
    public function updatedSearch()
    {
        $this->resetPage();
    }

    #[Computed()]
    public function Submissions()
    {
        $user_id = Auth::user()->id;
        
        return Submission::query()
        ->with(['user', 'departement', 'develop_progres.development_task'])
        ->where('user_id', $user_id)
        ->when($this->search, fn($q) => $q->where('application_name', 'like', "%{$this->search}%"))
        ->latest()
        ->paginate(10);
    }
};
?>

<div>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Pengajuan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/pengajuan/dashboard" wire:navigate>Dashboard</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">Pengajuan</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="container-fluid p-0">
                <div class="card border-0 shadow-sm">
                    <div class="card-body px-2">

                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2 py-3 header-container">
                            <div class="search-box" style="width: 100%; max-width: 300px;">
                                <div class="position-relative">
                                    <i
                                        class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                    <input type="text" wire:model.live.debounce.500ms="search" class="form-control ps-5"
                                        placeholder="Cari nama aplikasi...">
                                </div>
                            </div>
                            <a href="/pengajuan/create" wire:navigate
                                class="btn btn-sm btn-outline-success d-flex align-items-center gap-2 shadow-sm fw-medium px-4 py-2">
                                <i class="bi bi-plus-lg"></i>
                                <span class="d-none d-sm-inline">Buat Pengajuan</span>
                                <span class="d-inline d-sm-none">Buat</span>
                            </a>
                        </div>

                        <div class="table-responsive" style="overflow: visible;">
                            <table class="table table-hover align-middle mb-0" style="width: 100%;">
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
                                    @forelse ($this->submissions as $submission)
                                    <tr class="border-bottom">
                                        <td data-label="No. Tiket" class="ps-3">
                                            <span
                                                class="badge bg-danger-subtle text-danger border border-danger-subtle fw-normal px-2">
                                                {{$submission->no_ticket}}
                                            </span>
                                        </td>
                                        <td data-label="Nama Aplikasi" class="fw-bold text-dark">
                                            {{$submission->application_name}}</td>
                                        <td data-label="Pemohon" class="text-nowrap">{{ $submission->user->name }}</td>
                                        <td data-label="Unit Kerja">
                                            {{ $submission->departement->name }}
                                        </td>
                                        <td data-label="Jenis">
                                            <span class="badge bg-success px-2 py-1" style="font-weight: 500;">
                                                {{ $submission->type_development_label }}
                                            </span>
                                        </td>
                                        <td data-label="Status" class="text-center">
                                            @if($submission->develop_progres->count() > 0)
                                            @php
                                            $currentTask = $submission->develop_progres->where('status',
                                            'proses')->first();
                                            $completedCount = $submission->develop_progres->where('status',
                                            'selesai')->count();
                                            $totalCount = $submission->develop_progres->count();
                                            $percent = ($totalCount > 0) ? ($completedCount / $totalCount) * 100 : 0;
                                            @endphp

                                            <div class="mb-1">
                                                @if($currentTask)
                                                <span
                                                    class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 shadow-sm d-block">
                                                    <i class="bi bi-gear-fill spin small me-1"></i> {{
                                                    $currentTask->development_task->task_name }}
                                                </span>
                                                @else
                                                <span class="badge bg-success px-2 py-1 shadow-sm d-block">
                                                    <i class="bi bi-check-all me-1"></i> Selesai
                                                </span>
                                                @endif
                                            </div>

                                            <div style="font-size: 9px;" class="text-muted fw-bold">
                                                {{ round($percent) }}% Completed
                                                <div class="progress mt-1" style="height: 4px;">
                                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                        style="width: {{ $percent }}%">
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <span
                                                class="badge bg-{{ $submission->status_color }} px-2 py-1 shadow-sm d-block">
                                                {{ $submission->status_label }}
                                            </span>
                                            @endif
                                        </td>
                                        <td data-label="Tanggal" class="text-nowrap text-muted">
                                            {{ $submission->submission_date->format('d F Y') }}
                                        </td>
                                        <td data-label="Aksi" class="text-center pe-3">
                                            <a href="{{ route('pengajuan.show', $submission->no_ticket) }}"
                                                wire:navigate
                                                class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1"
                                                style="padding: 2px 8px; font-size: 11px;">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">
                                            Tidak ada data pengajuan
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $this->submissions->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </section>
    </main>
    <x-alert />
</div>