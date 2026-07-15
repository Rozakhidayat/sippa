<?php

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Submission;

new class extends Component
{
    public Submission $submission;

    public function mount(string $no_ticket)
    {
        $this->submission = Submission::firstWhere('no_ticket', $no_ticket);
        
    }
};
?>

<div>
    <main id="main" class="main">
        <div class="pagetitle mb-3 d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Pengajuan Detail</h1>
            <a href="/pengajuan" wire:navigate class="btn btn-danger btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center stepper">
                @if($submission->develop_progres->count() > 0)
                @foreach($submission->develop_progres->sortBy('development_task.sort_order') as $index => $progres)
                @if($index > 0)
                <div
                    class="step-line {{ $progres->status === 'selesai' || $progres->status === 'proses' ? 'done' : '' }}">
                </div>
                @endif

                <div class="text-center flex-fill position-relative">
                    <div
                        class="step-circle {{ $progres->status === 'selesai' ? 'done' : ($progres->status === 'proses' ? 'active' : '') }}">
                        @if($progres->status === 'selesai')
                        <i class="bi bi-check-circle-fill"></i>
                        @elseif($progres->status === 'proses')
                        <i class="bi bi-gear-fill spin"></i>
                        @else
                        <i class="bi bi-circle"></i>
                        @endif
                    </div>
                    {{-- Label Task --}}
                    <div class="small mt-2 fw-bold" style="font-size: 11px;">{{ $progres->development_task->task_name }}
                    </div>
                    <div class="extra-small text-muted" style="font-size: 9px;">{{ strtoupper($progres->status) }}</div>
                </div>
                @endforeach

                @else
                <div class="text-center flex-fill position-relative">
                    <div class="step-circle done"><i class="bi bi-send"></i></div>
                    <div class="small mt-2">Submitted</div>
                </div>

                @foreach(\App\Models\Workflow::where('is_active', true)->orderBy('sort_order', 'asc')->get() as $step)
                <div class="step-line {{ $submission->getStepStatus($step->state_code) }}"></div>

                <div class="text-center flex-fill position-relative">
                    <div class="step-circle {{ $submission->getStepStatus($step->state_code) }}">
                        <i
                            class="bi {{ $step->state_code === 'disetujui' ? 'bi-check-circle-fill' : 'bi-person' }}"></i>
                    </div>
                    <div class="small mt-2">{{ $step->label }}</div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
        <section class="section">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <div class="fw-bold mb-4">
                            <i class="bi bi-info-circle me-2 text-success"></i> Informasi Pengajuan
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
                    </div>

                    <div class="card border-0 shadow-sm p-4 mt-4">
                        <div class="mb-4 text-start">
                            <div class="fw-bold mb-1"><i class="bi bi-info-circle me-2 text-success"></i> Detail
                                Pengajuan
                            </div>
                            <div class="text-muted small">Informasi lengkap pengajuan aplikasi</div>
                        </div>
                        <div class="mb-3">
                            <div class="small mb-1 fw-semibold">Nama Aplikasi</div>
                            <div class="small text-primary fw-semibold">{{ $submission->application_name }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-semibold small mb-1">Latar Belakang</div>
                            <div class="small">{{ $submission->background }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="fw-semibold small mb-1">Lingkup Aplikasi</div>
                            <div class="small">{{ $submission->application_scope }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="fw-semibold small mb-1">Cost Benefit</div>
                            <div class="small">{{ $submission->cost_benefit }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="fw-semibold small mb-1">Resiko Jika tidak dibuat</div>
                            <div class="small">{{ $submission->risk }}</div>
                        </div>

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

                    <div class="card shadow-sm mb-4 border-0 mt-4">
                        <div class="card-body p-4">
                            <h5 class="fw-semibold mb-3 text-primary text-start">History Activity</h5>
                            @php
                            $groupedHistories = $submission->histories()->latest()->get()->groupBy(fn($item) =>
                            $item->created_at->format('d F Y'));
                            @endphp
                            @forelse($groupedHistories as $date => $logs)
                            <div class="mb-4 text-start">
                                <ul class="list-group list-group-flush">
                                    @foreach($logs as $log)
                                    <li class="list-group-item px-0 border-0">
                                        <div class="d-flex align-items-start gap-3 p-2">
                                            @if ($log->user->avatars)
                                            <img src="{{ asset('storage/' . $log->user->avatars) }}" alt="Profile"
                                                class="rounded-circle"
                                                style="width: 40px; height: 40px; font-size: 12px;">
                                            @else
                                            <div class="rounded-circle border shadow-sm flex-shrink-0 d-flex align-items-center justify-content-center bg-primary text-white fw-bold"
                                                style="width: 40px; height: 40px; font-size: 12px;">
                                                {{ $log->user->initial_name }}
                                            </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="fw-bold small">{{ $log->user->name }} <span
                                                        class="text-muted fw-normal">({{ $log->user->departement->name
                                                        }})</span></div>
                                                @php
                                                $parts = explode('|', $log->information);
                                                $statusTeks = $parts[0];
                                                $catatanTeks = $parts[1] ?? null;
                                                @endphp
                                                <div class="text-dark small">{{ $statusTeks }}</div>

                                                @if($catatanTeks)
                                                <div class="small fw-bold mt-2 text-info">
                                                    <span>Informasi :</span>
                                                    "{{ $catatanTeks }}"
                                                </div>
                                                @endif
                                                <div class="extra-small text-muted mt-1"><i class="bi bi-clock"></i> {{
                                                    $log->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @empty
                            <div class="text-center py-4 text-muted small">Belum ada aktivitas.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 mb-4 text-start">

                        <div class="mb-3">
                            @php
                            $hasDevelopment = $submission->develop_progres->count() > 0;
                            $activeTask = $submission->develop_progres->where('status', 'proses')->first();
                            @endphp

                            @if(!$hasDevelopment)
                            <div class="fw-semibold mb-4"><i class="bi bi-activity me-2 text-success"></i> Info Status
                            </div>
                            <div class="text-muted small">Status Saat Ini</div>
                            <span class="badge bg-{{ $submission->status_color }}">
                                {{ $submission->status_label }}
                            </span>
                            @else

                            <div class="fw-semibold mb-4"><i class="bi bi-activity me-2 text-success"></i> Progres Saat
                                Ini</div>
                            <div class="text-muted small">Progres Pengembangan</div>
                            <span class="badge bg-primary">
                                @php
                                $activeTask = $submission->develop_progres->where('status', 'proses')->first();
                                @endphp

                                @if($activeTask)
                                {{ $activeTask->development_task->task_name }}
                                @else
                                <i class="bi bi-check-all me-1"></i>
                                {{ $submission->status === 'selesai'
                                ? 'Selesai pada ' .
                                Carbon::parse($submission->completed_at)->timezone('Asia/Jakarta')->format('d M H:i')
                                : '-' }}
                                @endif
                            </span>
                            @endif
                        </div>

                        @if($submission->develop_progres->count() > 0)
                        @php
                        $done = $submission->develop_progres->where('status', 'selesai')->count();
                        $total = $submission->develop_progres->count();
                        $percent = ($total > 0) ? ($done / $total) * 100 : 0;
                        @endphp
                        <div class="mt-4 p-3 bg-light rounded border">
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-muted fw-bold">Overall Progress</span>
                                <span class="text-primary fw-bold">{{ round($percent) }}%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                    style="width: {{ $percent }}%">
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block" style="font-size: 10px;">
                                {{ $done }} dari {{ $total }} tahapan selesai
                            </small>
                        </div>
                        @endif
                        <div class="mt-3">
                            <div class="text-muted small">Tanggal Pengajuan</div>
                            <div class="fw-semibold">{{ $submission->submission_date->format('d F Y') }}</div>
                        </div>
                    </div>

                    @if (str_contains(strtolower($submission->status), 'revision'))
                    <div
                        class="card border-0 shadow-sm p-4 mb-4 text-start border-start border-warning border-4 bg-light">
                        <h6 class="fw-bold mb-2">Catatan Revisi</h6>
                        <p class="small text-muted mb-3">{{ $submission->last_remark }}</p>
                        <a href="{{ route('pengajuan.edit', $submission->no_ticket) }}" wire:navigate
                            class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square me-1"></i> Revisi Sekarang
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
</div>