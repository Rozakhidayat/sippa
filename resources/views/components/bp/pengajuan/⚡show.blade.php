<?php

use Livewire\Component;
use App\Models\Submission;
use Livewire\WithFileUploads;
use App\Livewire\Forms\SubmissionForm;

new class extends Component
{
    use WithFileUploads;

    public Submission $submission;
    public SubmissionForm $form;

    public function mount(string $no_ticket)
    {
        $this->submission = Submission::firstWhere('no_ticket', $no_ticket);
    }

    public function save()
    {
        $this->form->uploadBRD($this->submission);
        
        session()->flash('success', 'Berhasil Upload BRD');
        return $this->redirect('/bp/pengajuan', navigate: true);
    }
};
?>

<div>
    <div>
        <main id="main" class="main">
            <div class="pagetitle mb-3 d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Detail Pengajuan</h1>

                <a href="/bp/pengajuan" wire:navigate class="btn btn-danger btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <section class="section">
                <div class="row g-4">

                    <div class="col-lg-8">

                        <div class="card border-0 shadow-sm p-4">
                            <div class="fw-bold mb-4">
                                <i class="bi bi-info-circle me-2 text-success"></i>
                                Informasi Pengajuan
                            </div>

                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted small">No.Ticket</span>
                                <span class="fw-semibold text-danger">{{ $submission->no_ticket }}</span>
                            </div>

                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted small">Nama Pemohon</span>
                                <span class="fw-semibold">{{ $submission->user->name }}</span>
                            </div>

                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted small">Unit Kerja</span>
                                <span class="fw-semibold">{{ $submission->departement->name }}</span>
                            </div>

                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted small">Jenis Pengembangan</span>
                                <span class="badge bg-success">{{ $submission->type_development }}</span>
                            </div>
                            {{-- <div class="d-flex justify-content-between pt-2">
                                <span class="text-muted small">Business Partner</span>
                                <span class="fw-semibold">Rina BP</span>
                            </div> --}}
                        </div>

                        <div class="card border-0 shadow-sm p-4 mt-4">
                            <div class="mb-4">

                                <div class="fw-bold mb-1">
                                    <i class="bi bi-info-circle me-2 text-success"></i>
                                    Detail Pengajuan
                                </div>
                                <div class="text-muted small">Informasi lengkap pengajuan aplikasi</div>
                            </div>

                            <div class="mb-3">
                                <div class="small mb-1 fw-semibold">Nama Aplikasi</div>
                                <div class="small text-primary fw-semibold">{{ $submission->application_name }}</div>
                            </div>

                            <div class="mb-3">
                                <div class="fw-semibold small mb-1">Latar Belakang</div>
                                <div class="small">
                                    {{ $submission->background }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="fw-semibold small mb-1">Lingkup Aplikasi</div>
                                <div class="small">
                                    {{ $submission->application_scope }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="fw-semibold small mb-1">Cost Benefit</div>
                                <div class="small">
                                    {{ $submission->cost_benefit }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="fw-semibold small mb-1">Resiko Jika Tidak Dibuat</div>
                                <div class="small">
                                    {{ $submission->risk }}
                                </div>
                            </div>

                            <!-- THEMES -->
                            <div class="mt-4">
                                <div class="fw-semibold small mb-2">Kesesuaian IT Master Plan</div>
                                @php
                                $themesByCat = $submission->themes->groupBy(function($theme) {
                                return $theme->category ? $theme->category->name : 'TANPA KATEGORI';
                                });
                                @endphp
                                @forelse ($themesByCat as $category => $items)
                                <div class="mb-3">
                                    <div class="fw-semibold small text-primary mb-2">{{ $category }}</div>
                                    @foreach ($items as $theme)
                                    <span class="badge bg-light text-dark border me-1 text-wrap mt-2 mt-md-0">{{
                                        $theme->item }}</span>
                                    @endforeach
                                </div>
                                @empty
                                @endforelse

                            </div>
                        </div>
                    </div>

                    <!-- RIGHT -->
                    <div class="col-lg-4">

                        <div class="card border-0 shadow-sm p-4 mb-4">
                            <h6 class="fw-bold mb-4">
                                <i class="bi bi-activity me-2 text-success"></i>
                                Info Status
                            </h6>

                            <div class="mb-3">
                                <div class="text-muted small">Status</div>
                                <span class="badge bg-{{ $submission->status_color }}">
                                    {{ $submission->status_label}}
                                </span>
                            </div>

                            <div class="mb-0">
                                <div class="text-muted small">Tanggal Dibuat</div>
                                <div class="fw-semibold">{{ $submission->submission_date->format('d F Y') }}</div>
                            </div>
                        </div>

                        @if ($submission->status === 'perbaikan_brd')
                        <div
                            class="card border-0 shadow-sm p-4 mb-4 text-start border-start border-warning border-4 bg-light">
                            <h6 class="fw-bold mb-2">Catatan Revisi</h6>
                            <p class="small text-muted mb-3">{{ $submission->last_remark }}</p>
                        </div>
                        @endif

                        <div class="card border-0 shadow-sm p-4">
                            <h6 class="fw-bold mb-4">
                                Upload BRD
                            </h6>
                            <div class="mb-0">
                                <span>
                                    <div class="mb-3">
                                        <input wire:model='form.document_brd'
                                            class="form-control @error('form.document_brd') is-invalid @enderror"
                                            type="file" id="formFile" accept=".pdf,.doc,.docx">
                                        @error('form.document_brd')
                                        <div class=" invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    @if ($form->document_brd)
                                    <div class="mt-2 p-2 border rounded-3 bg-light d-flex align-items-center shadow-sm">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate mb-0">
                                                {{ $form->document_brd->getClientOriginalName() }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ number_format($form->document_brd->getSize() / 1024, 2) }} KB
                                            </div>
                                        </div>

                                        <button type="button" wire:click="$set('form.document_brd', null)"
                                            class="btn btn-outline-secondary btn-sm ms-3" title="Hapus file">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                    @endif
                                </span>
                            </div>

                            <div class="mt-3">
                                <button wire:click='save' wire:loading.attr='disabled' type="submit"
                                    class="btn btn-sm btn-outline-success">
                                    <span wire:loading wire:target='save' class="spinner-border spinner-border-sm"
                                        role="status" aria-hidden="true"></span> Kirim</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <x-alert />
    </div>
</div>