<?php

use Livewire\Component;
use App\Models\Submission;

new class extends Component
{
    public Submission $submission;
    public $actionType = '';
    public $last_remark = '';
    
    public function mount(string $no_ticket)
    {
        $this->submission = Submission::firstWhere('no_ticket', $no_ticket);
    }

    public function confirmAction(string $type)
    {
        $this->actionType = $type;
        
        if ($type == 'setuju')
        {
            $this->last_remark = 'Disetujui oleh SVP Unit Kerja';
            $this->submitAction();
        }else{
            $this->dispatch('openModalCreate');
        }
    }

    public function submitAction()
    {
        $this->validate(['last_remark' => 'required|min:5']);

        $this->submission->processAction($this->actionType, $this->last_remark);

        $this->dispatch('closeModal');
        session()->flash('success', 'Berhasil!');
        return $this->redirect('/svp/approval', navigate: true);
    }

};
?>

<div>
    <main id="main" class="main">
        <div class="pagetitle mb-3 d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Detail Pengajuan</h1>

            <a href="/svp/approval" wire:navigate class="btn btn-danger btn-sm">
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
                            <span class="badge bg-success">{{ $submission->type_development_label }}</span>
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
                                <span class="badge bg-light text-dark border me-1 text-wrap mt-2 mt-md-0">{{ $theme->item }}</span>
                                @endforeach
                            </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4">

                        <h6 class="fw-semibold mb-4">
                            Aksi Pengajuan
                        </h6>

                        <!-- STATUS -->
                        <div class="mb-4">
                            <div class="text-muted small mb-1">Status Saat Ini</div>
                            <span class="badge bg-{{ $submission->status_color }}">
                                {{ $submission->status_label }}
                            </span>
                        </div>

                        <!-- ACTION -->
                        <div class="d-grid gap-2">

                            <!-- SETUJUI -->
                            <button wire:click="confirmAction('setuju')" wire:loading.attr="disabled"
                                class="btn btn-outline-success btn-sm d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-check"></i>
                                <span wire:loading.remove wire:target="confirmAction('setuju')">Setujui</span>
                                <span wire:loading wire:target="confirmAction('setuju')">Proses...</span>
                            </button>

                            <!-- REVISI -->
                            <button wire:click="confirmAction('revisi')"
                                class="btn btn-outline-warning btn-sm d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-pencil"></i>
                                Revisi
                            </button>

                            <!-- TOLAK -->
                            <button wire:click="confirmAction('tolak')"
                                class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-x"></i>
                                Tolak
                            </button>
                        </div>

                        {{-- MODAL KONFIRMASI --}}
                        <div wire:ignore.self class="modal fade" id="create" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header 
                                                {{ $actionType === 'setuju' ? 'bg-success' : ($actionType === 'revisi' ? 'bg-warning' : 'bg-danger') }}
                                                text-white">
                                        <h6 class="modal-title small fw-bold">
                                            Konfirmasi {{ ucfirst($actionType) }} Pengajuan
                                        </h6>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <p class="small text-muted mb-3">
                                            Harap berikan catatan/alasan mengapa pengajuan ini <strong>{{ $actionType
                                                }}</strong>.
                                        </p>

                                        <label class="small fw-bold mb-2 text-dark">Catatan</label>
                                        <textarea wire:model="last_remark"
                                            class="form-control @error('last_remark') is-invalid @enderror" rows="4"
                                            placeholder="Tulis alasan atau catatan konfirmasi..."></textarea>
                                        @error('last_remark') <div class="invalid-feedback small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button wire:click="submitAction" wire:loading.attr='disabled'
                                            class="btn btn-sm {{ $actionType === 'setuju' ? 'btn-success' : ($actionType === 'revisi' ? 'btn-warning' : 'btn-danger') }} px-4">
                                            <span wire:loading wire:target='submitAction'
                                                class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span> Konfirmasi {{ ucfirst($actionType) }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <x-alert />
</div>