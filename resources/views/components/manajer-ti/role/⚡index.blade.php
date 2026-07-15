<?php

use App\Models\Role;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;
    
    public $search = '';

    #[On('createdRole')]
    #[On('editedRole')]
    
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function edit(int $id)
    {
        $this->dispatch('editRole', id: $id);
    }
    
    
    #[Computed()]
    public function roles()
    {
        return Role::query()
        ->withCount('users')
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
            <h1>Role</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">Roles</li>
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
                                        placeholder="Cari Role..." autocomplete="off">
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
                                        <th>Nama Role</th>
                                        <th>Jumlah User</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($this->roles as $role)
                                    <tr wire:key="role-{{ $role->id }}">
                                        <td>
                                            {{ $role->name }}
                                        </td>

                                        <td>
                                            <span class="badge bg-primary">{{ $role->users_count }} User</span>
                                        </td>

                                        <td>
                                            <button wire:click='edit({{ $role->id }})' class="btn btn-sm btn-outline-dark me-2"><i
                                                    class="bi bi-pencil-square"></i>
                                                Edit</button>
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
                            {{ $this->roles->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Modal -->
        <livewire:manajer-ti.role.create />
        <livewire:manajer-ti.role.edit />
    </main>
    <x-alert />
</div>