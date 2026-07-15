<?php

use App\Models\Role;
use Livewire\Component;
use App\Models\Departement;
use App\Livewire\Forms\UserForm;
use Livewire\Attributes\Computed;

new class extends Component
{
    public UserForm $form; 
    
    public $searchDepartement='';
    
    #[Computed()]
    public function roles()
    {
        return Role::pluck('name', 'id');
    }

    #[Computed()]
    public function departements()
    {
        return Departement::query()
        ->where('is_active', true)
        ->when($this->searchDepartement, function($q) {
            $q->where('name', 'like', "%{$this->searchDepartement}%");
        })
        ->get();
    }

    public function save()
    {
        $this->form->store();

        $this->dispatch('success', message: 'Data berhasil ditambahkan!');
        $this->dispatch('closeModal');
        $this->dispatch('createdUser');
    }

};
?>

<div>
    <div wire:ignore.self class="modal fade" id="create" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Tambah Data User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5">
                    <form class="row g-3" wire:submit.prevent='save'>
                        <div class="col-12">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('form.name') is-invalid @enderror"
                                id="unitKerja" wire:model='form.name'>
                            @error('form.name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="no_badge" class="form-label">Nomor Badge</label>
                            <input type="text" class="form-control @error('form.no_badge') is-invalid @enderror"
                                id="no_badge" wire:model='form.no_badge'>
                            @error('form.no_badge')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="role_id" class="form-label">Role</label>
                            <select wire:model='form.role_id' id="role_id"
                                class="form-select @error('form.role_id') is-invalid @enderror">
                                <option value="">Pilih Role</option>
                                @foreach ($this->roles as $id => $name)
                                <option value="{{ $id }}">{{$name}}</option>
                                @endforeach
                            </select>
                            @error('form.role_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12" x-data="{ open: false }">
                            <label class="form-label">Departement</label>

                            <div class="position-relative">
                                <div class="form-control d-flex justify-content-between align-items-center @error('form.departement_id') is-invalid @enderror"
                                    @click="open = !open" style="cursor: pointer;">

                                    <span>
                                        {{ $this->departements->firstWhere('id', $form->departement_id)->name ?? 'Pilih
                                        Departement' }}
                                    </span>

                                    <i class="bi bi-chevron-down small"></i>
                                </div>
                                @error('form.departement_id')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror

                                <div x-show="open" @click.away="open = false"
                                    class="position-absolute w-100 bg-white border rounded shadow-sm p-2"
                                    style="z-index: 1000; margin-top: 5px;">
                                    <input type="text" class="form-control mb-2 form-control-sm" placeholder="Cari..."
                                        wire:model.live.debounce.500ms="searchDepartement" @click.stop>

                                    <div class="list-group list-group-flush"
                                        style="max-height: 200px; overflow-y: auto;">
                                        @forelse ($this->departements as $departement)
                                        <label
                                            class="list-group-item list-group-item-action py-2 border-0 {{ $form->departement_id == $departement->id ? 'active' : '' }}"
                                            style="cursor: pointer;" @click="open = false">

                                            <input class="d-none" type="radio" value="{{ $departement->id }}"
                                                wire:model.live.debounce.150ms="form.departement_id">
                                            {{ $departement->name }}
                                        </label>
                                        @empty
                                        <div class="text-muted small text-center py-2">Data tidak ditemukan</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('form.email') is-invalid @enderror"
                                id="email" wire:model='form.email'>
                            @error('form.email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('form.password') is-invalid @enderror"
                                id="password" wire:model='form.password'>
                            @error('form.password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label">Konfirmasi Password</label>
                            <input type="password"
                                class="form-control @error('form.password_confirmation') is-invalid @enderror"
                                id="password" wire:model='form.password_confirmation'>
                            @error('form.password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success" wire:loading.attr='disabled'>
                                <span wire:loading wire:target='save' class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true"></span>
                                Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>