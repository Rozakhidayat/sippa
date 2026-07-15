<?php

use App\Models\Role;
use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Livewire\Forms\WorkflowForm;

new class extends Component
{
    public WorkflowForm $form;

    public function mount() 
    {
        $this->form->setNextOrder();
        
    }

    
    
    #[Computed()]
    public function roles()
    {
        return Role::pluck('name', 'id');
    }

    public function save()
    {
        $this->form->store();
        $this->dispatch('createdWorkflow');
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
                    <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Tambah Workflow</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5">
                    <form class="row g-3" wire:submit.prevent='save'>
                        <div class="col-12">
                            <label for="workflow" class="form-label">Label Status</label>
                            <input type="text" class="form-control @error('form.label') is-invalid @enderror"
                                id="workflow" wire:model.live.debounce.150ms='form.label'
                                placeholder="Contoh Pending Approval VP">
                            @error('form.label')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="workflow" class="form-label">Kode Status</label>
                            <input type="text"
                                class="form-control bg-light @error('form.state_code') is-invalid @enderror"
                                id="workflow" wire:model='form.state_code' readonly>
                            @error('form.state_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Warna Label (Badge)</label>
                            <select wire:model="form.color" class="form-select">
                                <option value="primary">Biru (Primary)</option>
                                <option value="warning">Kuning (Warning)</option>
                                <option value="danger">Merah (Danger)</option>
                                <option value="success">Hijau (Success)</option>
                                <option value="info">Biru Muda (Info)</option>
                                <option value="dark">Hitam (Dark)</option>
                                <option value="secondary">Abu-abu (Secondary)</option>
                            </select>
                            @error('form.color')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="role_id" class="form-label">Pilih orang yang memproses tahapan ini</label>
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

                        <div class="col-12">
                            <label for="workflow" class="form-label">Urutan Tahap </label>
                            <input type="number" class="form-control @error('form.sort_order') is-invalid @enderror"
                                id="workflow" wire:model='form.sort_order'>
                            @error('form.sort_order')
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
                    </form><!-- Vertical Form -->
                </div>
            </div>
        </div>
    </div>
</div>