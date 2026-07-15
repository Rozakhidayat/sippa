<?php

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;
    
    public $search = '';

    #[On('createdUser')]
    #[On('editedUser')]

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function edit(int $id)
    {
        $this->dispatch('editUser', id: $id);
    }
    
    #[Computed()]
    public function users()
    {
        return User::query()
        ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
        ->latest()
        ->paginate(6);
    }
};
?>

<div>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Users</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">Users</li>
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
                                        placeholder="Cari User..." autocomplete="off">
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
                                        <th>Nama</th>
                                        <th>Unit Kerja</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($this->users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    @if ($user->avatars)
                                                    <img src="{{ asset('storage/' . $user->avatars) }}" alt="avatar"
                                                        class="rounded-circle border"
                                                        style="width: 32px; height: 32px; object-fit: cover;">
                                                    @else
                                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white border"
                                                        style="width: 32px; height: 32px; font-size: 12px;">
                                                        {{ $user->initial_name }}
                                                    </div>
                                                    @endif
                                                </div>

                                                <div>
                                                    <div class="fw-semibold">{{ $user->name }}</div>
                                                    <small class="text-muted d-block" style="font-size: 11px;">{{
                                                        $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $user->departement->name }}
                                        </td>

                                        <td>
                                            {{ $user->role->name }}
                                        </td>
                                        <td>
                                            <button wire:click='edit({{ $user->id }})'
                                                class="btn btn-sm btn-outline-dark me-2"><i
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
                            {{ $this->users->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Modal -->
        <livewire:manajer-ti.user.create />
        <livewire:manajer-ti.user.edit />
    </main>
    <x-alert />
</div>