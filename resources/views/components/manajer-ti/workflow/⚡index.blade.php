<?php

use Livewire\Component;
use App\Models\Workflow;
use App\Models\Submission;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;


    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    #[On('createdWorkflow')]
    #[On('editedWorkflow')]

    #[Computed()]
    public function steps()
    {
        return Workflow::query()
            ->when($this->search, function($q) {
                $q->where('label', 'like', "%{$this->search}%");
            })
            ->orderBy('sort_order', 'asc')
            ->paginate(10);
    }

    public function edit( int $id)
    {
        $this->dispatch('editWorkflow', id: $id);
    }

    public function toggleStatus(int $id)
    {
        $step = Workflow::findOrFail($id);
        
        if ($step->is_active) {
            $hasActiveSubmissions = Submission::where('workflow_id', $id)
                ->whereNotIn('status', ['disetujui', 'rejected']) 
                ->exists();

            if ($hasActiveSubmissions) {
                $this->dispatch('error', message: 'Tidak dapat menonaktifkan! Masih ada pengajuan aktif yang berada di tahap ini.');
                return;
            }
        }

        $step->is_active = !$step->is_active;
        $step->save();
        
        $this->dispatch('success', message: 'Status berhasil diperbarui');
    }



};
?>

<div>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Alur Persetujuan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">Alur Persetujuan</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2 py-3">

                                <div class="position-relative" style="max-width: 300px;">
                                    <i
                                        class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                    <input type="text" wire:model.live.debounce.500ms="search" class="form-control ps-5"
                                        placeholder="Cari status.." autocomplete="off">
                                </div>

                                <button wire:click="$dispatch('openModalCreate')"
                                    class="btn btn-sm btn-outline-success text-start d-flex align-items-center gap-2 shadow-sm fw-medium px-4 py-2"
                                    type="button">
                                    <i class="bi bi-plus-lg"></i> Tambah
                                </button>

                            </div>

                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Workflow Step</th>
                                        <th>Status</th>
                                        <th>Diproses Oleh</th>
                                        <th>Activasi</th>
                                        <th>Aksi</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($this->steps as $step)
                                    <tr wire:key="step-{{ $step->id }}">
                                        <td>
                                            {{ $step->sort_order }}
                                        </td>

                                        <td>
                                            <span class="badge bg-{{ $step->color }} px-2 py-1">{{
                                                $step->label }}</span>
                                        </td>

                                        <td>
                                            {{ $step->role->name }}
                                        </td>

                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    wire:key="switch-{{ $step->id }}-{{ $step->is_active }}"
                                                    id="switch-{{ $step->id }}" {{ $step->is_active ?
                                                'checked' : '' }}
                                                wire:click="$event.preventDefault(); toggleStatus({{ $step->id }})"
                                                style="cursor: pointer;">
                                                <label class="form-check-label small" for="switch-{{ $step->id }}">
                                                    {{ $step->is_active ? 'Aktif' : 'Non-aktif' }}
                                                </label>
                                            </div>
                                        </td>

                                        <td>
                                            <button wire:click='edit({{ $step->id }})'
                                                class="btn btn-sm btn-outline-dark me-2">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Tidak ada data workflow
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $this->steps->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <livewire:manajer-ti.workflow.edit />
        <livewire:manajer-ti.workflow.create />
    </main>
    <x-alert />
</div>