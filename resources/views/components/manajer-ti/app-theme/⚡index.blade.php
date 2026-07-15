<?php

use App\Models\Theme;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;

    public string $search = '';

    #[On('createdTheme')]
    #[On('editedTheme')]
    
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus(int $id)
    {
        $theme = Theme::findOrFail($id);
        $theme->update([
            'is_active' => !$theme->is_active
        ]);
        $this->dispatch('success', message: 'Status berhasil diperbarui');
    }

    public function edit(int $id)
    {
        
        $this->dispatch('editTheme', id: $id);
    }

    #[Computed()]
    public function themes()
    {
        return Theme::query()
        ->when($this->search, function ($q) {
            $q->where('item', 'like', "%{$this->search}%");
        })
        ->latest()
        ->paginate(10);
    }
};
?>

<div>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tema Aplikasi</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item">Tables</li>
                    <li class="breadcrumb-item active">Tema Aplikasi</li>

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
                                        placeholder="Cari tema...">
                                </div>

                                <button wire:click="$dispatch('openModalCreate')"
                                    class="btn btn-sm btn-outline-success text-start d-flex align-items-center gap-2 shadow-sm fw-medium px-4 py-2"
                                    type="button">
                                    Tambah
                                </button>

                            </div>
                            <!-- Table with stripped rows -->
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%">
                                                Kategori
                                            </th>
                                            <th style="width: 35%">Tema</th>
                                            <th style="width: 20%">Periode</th>
                                            <th style="width: 20%">Status</th>
                                            <th style="width: 20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($this->themes as $theme)
                                        <tr>
                                            <td>{{ $theme->category->name }}</td>
                                            <td>
                                                {{ $theme->item }}
                                            </td>
                                            <td>
                                                {{ $theme->periode }}
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="switch-{{ $theme->id }}" {{ $theme->is_active
                                                    ? 'checked' : '' }}
                                                    wire:click="toggleStatus({{ $theme->id }})"
                                                    style="cursor: pointer;">
                                                    <label class="form-check-label small" for="switch-{{ $theme->id }}">
                                                        {{ $theme->is_active ? 'Aktif' : 'Non-aktif' }}
                                                    </label>
                                                </div>
                                            </td>

                                            <td>
                                                <button wire:click='edit({{ $theme->id }})'
                                                    class="btn btn-sm btn-outline-dark me-2"><i
                                                        class="bi bi-pencil-square"></i>
                                                    Edit</button>

                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                Tidak ada data
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $this->themes->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Modal -->
        <livewire:manajer-ti.app-theme.create />
        <livewire:manajer-ti.app-theme.edit />
    </main>
    <x-alert />
</div>