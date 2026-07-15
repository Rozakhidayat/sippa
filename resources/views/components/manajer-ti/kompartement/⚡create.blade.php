<?php

use Livewire\Component;
use App\Models\Direktorat;
use App\Livewire\Forms\KompartementForm;

new class extends Component
{
    public KompartementForm $form;
    
    public $searchDirektorat = '';
    
    public function getFilteredDirektoratsProperty()
    {
        return Direktorat::query()
        ->when($this->searchDirektorat, function($q) {
            $q->where('name', 'like', "%{$this->searchDirektorat}%");
        })
        ->get();
    }

    public function save()
    {
        $this->form->store();
        $this->dispatch('createdKompartement');
        $this->dispatch('success', message: 'Data berhasil ditambahkan!');
        $this->dispatch('closeModal');
    }
};
?>

<div>
    <div wire:ignore.self class="modal fade" id="create" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Tambah Data Kompartement</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5">
                    <form class="row g-3" wire:submit.prevent='save'>
                        <div class="col-12">
                            <label for="kompartement" class="form-label">Kompartement</label>
                            <input type="text" class="form-control @error('form.name') is-invalid @enderror"
                                id="kompartement" wire:model='form.name'>
                            @error('form.name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12" x-data="{ open: false }">
                            <label class="form-label">Direktorat</label>

                            <div class="position-relative">
                                <div class="form-control d-flex justify-content-between align-items-center @error('form.direktorat_id') is-invalid @enderror"
                                    @click="open = !open" style="cursor: pointer;">
                                    <span>{{Direktorat::find($form->direktorat_id)?->name ?: 'Pilih
                                        Direktorat' }}</span>
                                    <i class="bi bi-chevron-down small"></i>
                                </div>

                                @error('form.direktorat_id')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                                <div x-show="open" @click.away="open = false"
                                    class="position-absolute w-100 bg-white border rounded shadow-sm p-2"
                                    style="z-index: 1000; margin-top: 5px;">

                                    <input type="text" class="form-control mb-2 form-control-sm" placeholder="Cari..."
                                        wire:model.live.debounce.500ms="searchDirektorat" @click.stop>

                                    <div class="list-group list-group-flush"
                                        style="max-height: 200px; overflow-y: auto;">
                                        @forelse ($this->filteredDirektorats as $item)
                                        <label
                                            class="list-group-item list-group-item-action py-2 border-0 {{ $form->direktorat_id === $item->id ? 'active' : '' }}"
                                            style="cursor: pointer;" @click="open = false"> <input class="d-none"
                                                type="radio" value="{{ $item->id}}"
                                                wire:model.live.debounce.150ms="form.direktorat_id">
                                            {{ $item->name }}
                                        </label>

                                        @empty
                                        <div class="text-muted small text-center py-2">Data tidak ditemukan</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success" wire:loading.attr='disabled'>
                                <span wire:loading wire:target='save' class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true"></span>
                                Kirim</button>
                        </div>
                    </form><!-- Vertical Form -->
                </div>
            </div>
        </div>
    </div>
</div>