<?php

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\DevelopmentTask;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;
    
    public $search = '';
    public $deleteId;

    #[On('createdDevelopTask')]
    #[On('updatedDevelopTask')]
    #[On('deletedTask')]
    
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function edit(int $id)
    {
        $this->dispatch('editTask', id: $id);
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
            $development_task= DevelopmentTask::findOrFail($this->deleteId);
            if ($development_task->develop_progres()->exists()) {
                $this->dispatch('error', message: 'Gagal menghapus! Data sudah digunakan');
            } else {
                $development_task->delete();
                $this->reset('deleteId'); 
                $this->dispatch('deletedTask'); 
                $this->dispatch('success', message: 'Data berhasil dihapus');
            }
        }
    }

    
    #[Computed()]
    public function tasks()
    {
        return DevelopmentTask::query()
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('type_development', 'like', "%{$this->search}%")
                        ->orWhere('task_name', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('sort_order', 'asc')
            ->paginate(10);
    }

};
?>

<div>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tahapan Pengembangan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">Tahapan Pengembangan</li>
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
                                        placeholder="Cari tipe pengembangan.." autocomplete="off">
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
                                        <th>Urutan Tahapan</th>
                                        <th>Tipe Pengembangan</th>
                                        <th>Nama Tahapan</th>
                                        <th>Diproses Oleh</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($this->tasks as $task)
                                    <tr>
                                        <td>
                                            {{ $task->sort_order }}
                                        </td>

                                        <td>
                                            {{ $task->type_development_label }}
                                        </td>

                                        <td>
                                            {{ $task->task_name }}
                                        </td>

                                        <td>
                                            {{ $task->role->name }}
                                        </td>

                                        <td>
                                            <button wire:click='edit({{ $task->id }})' class="btn btn-sm btn-outline-dark me-2"><i
                                                    class="bi bi-pencil-square"></i>
                                                Edit</button>

                                            <button wire:click='confirmDelete({{ $task->id }})' class="btn btn-sm btn-outline-danger me-2"><i
                                                    class="bi bi-trash"></i>
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
                            {{ $this->tasks->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Modal -->
        <livewire:manajer-ti.development_task.create />
        <livewire:manajer-ti.development_task.edit />
    </main>
    <x-alert />
</div>