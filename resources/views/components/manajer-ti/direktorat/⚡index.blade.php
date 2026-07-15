<?php

use Livewire\Component;
use App\Models\Direktorat;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;
    
    public $search = '';
    public $deleteId;

    #[On('createdDirektorat')]
    #[On('editedDirektorat')]
    #[On('deletedDirektorat')]
    
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function edit(int $id)
    {
        $this->dispatch('editDirektorat', id: $id);
    }

    public function confirmDelete(int $id)
    {
        $this->deleteId = $id; 
        $this->dispatch('show-delete-confirmation'); 
    }

    #[On('deleteConfirmed')]
    public function delete()
    {
        if ($this->deleteId) {
            $direktorat = Direktorat::findOrFail($this->deleteId);
            if ($direktorat->kompartements()->exists()) {
                $this->dispatch('error', message: 'Gagal menghapus! Data sudah digunakan');
            } else {
                $direktorat->delete();
                $this->reset('deleteId'); 
                $this->dispatch('deletedDirektorat'); 
                $this->dispatch('success', message: 'Data berhasil dihapus');
            }
        }
    }

    #[Computed()]
    public function direktorats()
    {
        return Direktorat::query()
        ->when($this->search, function ($q) {
            $q->where('name', 'like', "%{$this->search}%");
        })
        ->latest()
        ->paginate(6);
    }
};
?>

<div>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Direktorat</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">Direktorat</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2 py-3 gap-3">
                                <div class="position-relative w-100" style="max-width: 300px;">
                                    <i
                                        class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                    <input type="text" wire:model.live.debounce.500ms="search" class="form-control ps-5"
                                        placeholder="Cari Direktorat..." autocomplete="off">
                                </div>

                                <button wire:click="$dispatch('openModalCreate')"
                                    class="btn btn-sm btn-outline-success d-flex align-items-center justify-content-center gap-2 shadow-sm fw-medium px-4 py-2"
                                    type="button">
                                    Tambah
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Direktorat</th>
                                            <th style="min-width: 180px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($this->direktorats as $direktorat)
                                        <tr wire:key="direktorat-{{ $direktorat->id }}">
                                            <td>{{ $direktorat->name }}</td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <button wire:click='edit({{ $direktorat->id }})'
                                                        class="btn btn-sm btn-outline-dark"><i
                                                            class="bi bi-pencil-square"></i> Edit</button>

                                                    <button wire:click='confirmDelete({{ $direktorat->id }})'
                                                        class="btn btn-sm btn-outline-danger"><i
                                                            class="bi bi-trash"></i> Hapus</button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Tidak ada data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{ $this->direktorats->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <livewire:manajer-ti.direktorat.create />
        <livewire:manajer-ti.direktorat.edit />
    </main>
    <x-alert />
</div>