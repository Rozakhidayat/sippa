<?php

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Submission;
use App\Models\Departement;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SubmissionExport;
use Maatwebsite\Excel\Facades\Excel;

new class extends Component
{
    public $hasFilter = false;
    public ?int $filter_dept = null ;
    public ?string $filter_status = '';
    public ?string $date_from = '';
    public ?string $date_to = '';
    public $searchDepartement = '';

    public function applyFilter()
    {
        $this->hasFilter = true;
        
    }

    public function exportPdf()
    {
        $data = $this->getFilter();

        if ($data->isEmpty()) {
            $this->dispatch('error', 
                type: 'error', 
                message: 'Gagal cetak! Data tidak ditemukan'
            );
            return; 
        }

        $imagePath = public_path('assets/img/icon-favicon.png');
        $logoBase64 = '';
        if (file_exists($imagePath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($imagePath));
        }

        if ($this->date_from && $this->date_to){
            $periode_date = Carbon::parse($this->date_from)->format('d F Y') . ' ' . 's/d' . ' ' . Carbon::parse($this->date_to)->format('d F Y');
        }elseif ($this->date_from){
            $periode_date = Carbon::parse($this->date_from)->format('d F Y');
        }elseif ($this->date_to){
            $periode_date = Carbon::parse($this->date_to)->format('d F Y');
        }else{
            $periode_date = 'Semua Periode';
        }
        
        $departements = Departement::query()
            ->where('id', $this->filter_dept)
            ->value('name') ?? 'Semua Departement';

        
        $pdf = Pdf::loadView('pdf.laporan', [
            'submissions' => $data,
            'departements' => $departements,
            'periode'      => $periode_date,
            'logo'         => $logoBase64
        ])->setPaper('a4', 'portrait');
        
        return response()->streamDownload(function () use ($pdf) {
        echo $pdf->output();
        }, 'Laporan_SIPPA_' . now()->format('YmdHi') . '.pdf');
        
    }

    private function getFilter()
    {
        return Submission::query()
            ->when($this->filter_dept, fn($q) => $q->where('departement_id', $this->filter_dept))
            ->when($this->filter_status, function($q) {
                if ($this->filter_status === 'selesai') {
                    $q->whereHas('develop_progres')
                    ->whereDoesntHave('develop_progres', function($subQ) {
                        $subQ->where('status', '!=', 'selesai');
                    });
                } else {
                    $q->where('status', $this->filter_status);
                }
            }, fn($q) => $q->whereIn('status', ['selesai', 'rejected'])
            )
            ->when($this->date_from, fn($q) => $q->where('submission_date', '>=', $this->date_from))
            ->when($this->date_to, fn($q) => $q->where('submission_date', '<=', $this->date_to))
            ->latest()
            ->get();
    }
    public function exportExcel()
    {
        $data = $this->getFilter();

        if ($this->date_from && $this->date_to){
            $periode_date = Carbon::parse($this->date_from)->format('d F Y') . ' ' . 's/d' . ' ' . Carbon::parse($this->date_to)->format('d F Y');
        }elseif ($this->date_from){
            $periode_date = Carbon::parse($this->date_from)->format('d F Y');
        }elseif ($this->date_to){
            $periode_date = Carbon::parse($this->date_to)->format('d F Y');
        }else{
            $periode_date = 'Semua Periode';
        }

        $departements = Departement::query()
            ->where('id', $this->filter_dept)
            ->value('name') ?? 'Semua Departement';

        if ($data->isEmpty()) {
            $this->dispatch('error', 
                type: 'error', 
                message: 'Gagal cetak! Data tidak ditemukan'
            );
        return; 
        }
    return Excel::download(new SubmissionExport($data, $periode_date, $departements), 'Laporan_SIPA_' . now()->format('YmdHi') . '.xlsx');
    }

    public function render()
    {
        $submissions = $this->hasFilter ? $this->getFilter() : collect();
        
        return $this->view([
            'submissions' => $submissions,
            'departements' => Departement::query()
                ->where('name', 'like', '%' . ($this->searchDepartement ?? '') . '%')
                ->get()
        ]);
    }
};
?>

<div>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Laporan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">Laporan</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="container-fluid p-0">
                <div class="card border-0 shadow-sm">
                    <div class="card-body px-2">
                        <div class="row g-3 mb-4 py-3 border-bottom d-print-none align-items-end">



                            <div class="col-md-3" x-data="{ open: false }">
                                <label class="form-label small fw-bold">Departemen</label>
                                <div class="position-relative">
                                    <div class="form-control form-control-sm d-flex justify-content-between align-items-center"
                                        @click="open = !open" style="cursor: pointer;">
                                        <span class="small">{{ $departements->firstWhere('id',
                                            $filter_dept)->name
                                            ?? 'Semua Departement' }}</span>
                                        <i class="bi bi-chevron-down"></i>
                                    </div>

                                    <div x-show="open" @click.away=" open=false"
                                        class="position-absolute w-100 bg-white border rounded shadow-sm p-2 mt-2"
                                        style="z-index: 1000;">
                                        <input type="text" class="form-control form-control-sm mb-2"
                                            placeholder="Cari..." wire:model.live.debounce.500ms="searchDepartement">
                                        <div class="list-group list-group-flush"
                                            style="max-height: 200px; overflow-y: auto;">

                                            <button type="button"
                                                class="list-group-item list-group-item-action small py-1"
                                                wire:click="$set('filter_dept', '')" @click="open = false">
                                                <em>Semua Departemen</em>
                                            </button>

                                            @foreach ($departements as $dept)
                                            <button type="button" class="list-group-item list-group-item-action small"
                                                wire:click="$set('filter_dept', {{ $dept->id }})" @click="open = false">
                                                {{ $dept->name }}
                                            </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small text-muted fw-semibold mb-1">Status</label>
                                <select wire:model="filter_status" class="form-select form-select-sm">
                                    <option value="">Semua Status</option>
                                    <option value="selesai">Selesai
                                    </option>
                                    <option value="rejected">Ditolak</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small text-muted fw-semibold mb-1">Periode</label>
                                <div class="input-group input-group-sm">
                                    <input type="date" wire:model="date_from" class="form-control">
                                    <span class="input-group-text">s/d</span>
                                    <input type="date" wire:model="date_to" class="form-control">
                                </div>
                            </div>

                            <div class="{{ $hasFilter ? 'col-md-3' : 'col-md-2' }}">
                                <div class="d-flex gap-2">
                                    <button type="button" wire:click="applyFilter" wire:loading.attr="disabled"
                                        class="btn btn-primary btn-sm w-100">
                                        <span wire:loading wire:target="applyFilter"
                                            class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        <i wire:loading.remove wire:target="applyFilter" class="bi bi-filter">Filter</i>
                                    </button>


                                    @if ($hasFilter)
                                    <button type="button" wire:click="exportPdf" wire:loading.attr="disabled"
                                        class="btn btn-danger btn-sm w-100">
                                        <span wire:loading wire:target="exportPdf"
                                            class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        <i wire:loading.remove wire:target="exportPdf"
                                            class="bi bi-file-earmark-pdf">PDF</i>
                                    </button>

                                    <button type="button" wire:click="exportExcel" wire:loading.attr="disabled"
                                        class="btn btn-success btn-sm w-100">
                                        <span wire:loading wire:target="exportExcel"
                                            class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        <i wire:loading.remove wire:target="exportExcel"
                                            class="bi bi-file-earmark-excel">Excel</i>
                                    </button>
                                    @endif

                                </div>
                            </div>

                        </div>

                        <div wire:loading wire:target="applyFilter" class="text-center w-100 py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Sedang mengambil data laporan...</p>
                        </div>

                        <div wire:loading.remove wire:target='applyFilter'>
                            @if($hasFilter)
                            <div class="table-responsive">
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
                                            {{-- <th class="py-3 text-center pe-3">Aksi</th> --}}
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
                                            <td class="text-nowrap text-muted">{{
                                                $submission->submission_date->format('d F
                                                Y')
                                                }}</td>
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
                            @else
                            <div class="text-center py-5">
                                <i class="bi bi-info-circle fs-2 text-muted"></i>
                                <p class="text-muted mt-2">Silakan atur filter dan klik "Filter" untuk
                                    menampilkan laporan.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <x-alert />
</div>