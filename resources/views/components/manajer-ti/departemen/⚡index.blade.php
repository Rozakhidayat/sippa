<?php

use Livewire\Component;
use App\Models\Departement;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;
    
    public $search = '';
    public $deleteId;
    public int $id;
    
    #[On('createdDepartement')]
    #[On('editedDepartement')]
    #[On('deleteddepartement')]
    
    public function updatedSearch()
    {
        $this->resetPage();
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
            $departement= Departement::findOrFail($this->deleteId);
            if ($departement->users()->exists()) {
                $this->dispatch('error', message: 'Gagal menghapus! Data sudah digunakan');
            } else {
                $departement->delete();
                $this->reset('deleteId'); 
                $this->dispatch('deleteddepartement'); 
                $this->dispatch('success', message: 'Data berhasil dihapus');
            }
        }
    }

    public function toggleStatus(int $id)
    {
        $departement = Departement::findOrFail($id);
        $departement->update([
            'is_active' => !$departement->is_active
        ]);
        $this->dispatch('success', message: 'Status berhasil diperbarui');
    }

    public function edit(int $id)
    {
        $this->dispatch('editDepartemen', id: $id);
    }

    #[Computed()]
    public function departements()
    {
        return Departement::query()
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
            <h1>Unit Kerja</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">Unit Kerja</li>
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
                                        placeholder="Cari Unit Kerja..." autocomplete="off">
                                </div>

                                <button wire:click="$dispatch('openModalCreate')"
                                    class="btn btn-sm btn-outline-success text-start d-flex align-items-center gap-2 shadow-sm fw-medium px-4 py-2"
                                    type="button">
                                    Tambah
                                </button>

                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Unit Kerja</th>
                                            <th scope="col">Kompartement</th>
                                            <th scope="col">Direktorat</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($this->departements as $departement)
                                        <tr wire:key="departement-{{ $departement->id }}">
                                            <td>{{ $departement->name }}</td>
                                            <td>{{ $departement->kompartement->name ?? '-' }}</td>
                                            <td>{{ $departement->kompartement->direktorat->name ?? '-' }}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="switch-{{ $departement->id }}" {{ $departement->is_active ?
                                                    'checked' : '' }}
                                                    wire:click="toggleStatus({{ $departement->id }})"
                                                    style="cursor: pointer;">
                                                    <label class="form-check-label small"
                                                        for="switch-{{ $departement->id }}">
                                                        {{ $departement->is_active ? 'Aktif' : 'Non-aktif' }}
                                                    </label>
                                                </div>
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <div class="d-flex flex-row gap-2">
                                                    <button wire:click='edit({{ $departement->id }})'
                                                        class="btn btn-sm btn-outline-dark"><i
                                                            class="bi bi-pencil-square"></i> Edit</button>
                                                    <button wire:click='confirmDelete({{ $departement->id }})'
                                                        class="btn btn-sm btn-outline-danger"><i
                                                            class="bi bi-trash"></i> Hapus</button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted p-3">
                                                Tidak ada data
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $this->departements->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Modal -->
        <livewire:manajer-ti.departemen.create />
        <livewire:manajer-ti.departemen.edit />
    </main>
    <x-alert />
</div>