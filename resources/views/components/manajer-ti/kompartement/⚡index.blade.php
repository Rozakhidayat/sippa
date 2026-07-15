<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Kompartement;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;
    
    public $search = '';
    public $deleteId;
    
    
    #[On('createdKompartement')]
    #[On('editedKompartement')]
    #[On('deletedKompartement')]

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function edit(int $id)
    {
        $this->dispatch('editKompartement', id: $id);
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
            $kompartement= Kompartement::findOrFail($this->deleteId);
            if ($kompartement->departements()->exists()) {
                $this->dispatch('error', message: 'Gagal menghapus! Data sudah digunakan');
            } else {
                $kompartement->delete();
                $this->reset('deleteId'); 
                $this->dispatch('deletedkompartement'); 
                $this->dispatch('success', message: 'Data berhasil dihapus');
            }
        }
    }

    #[Computed()]
    public function kompartements()
    {
        return Kompartement::query()
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
            <h1>Kompartement</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">Kompartement</li>
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
                                        placeholder="Cari Kompartement..." autocomplete="off">
                                </div>

                                <button wire:click="$dispatch('openModalCreate')"
                                    class="btn btn-sm btn-outline-success text-start d-flex align-items-center gap-2 shadow-sm fw-medium px-4 py-2"
                                    type="button">
                                    Tambah
                                </button>

                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kompartement</th>
                                        <th>Direktorat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($this->kompartements as $kompartement)
                                    <tr>
                                        <td>
                                            {{ $kompartement->name}}
                                        </td>
                                        <td>
                                            {{ $kompartement->direktorat->name }}
                                        </td>
                                        <td>
                                            <button wire:click='edit({{ $kompartement->id }})'
                                                class="btn btn-sm btn-outline-dark me-2"><i
                                                    class="bi bi-pencil-square"></i>
                                                Edit</button>

                                            <button wire:click='confirmDelete({{ $kompartement->id }})'
                                                class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i>
                                                Hapus</button>
                                        </td>
                                        @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            Tidak ada data
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $this->kompartements->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Modal -->
        <livewire:manajer-ti.kompartement.create />
        <livewire:manajer-ti.kompartement.edit />
    </main>
    <x-alert />
</div>