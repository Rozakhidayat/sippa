<?php

use App\Models\Role;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\DevelopmentTask;
use Livewire\Attributes\Computed;
use App\Livewire\Forms\DevelopmentTaskForm;

new class extends Component
{
    public DevelopmentTaskForm $form;

    public function mount() 
    {
        $this->form->role_id = 8;
    }

    #[Computed()]
    public function roles()
    {
        return Role::pluck('name', 'id');
    }

    #[On('editTask')]
    public function load(int $id)
    {
        $task = DevelopmentTask::findOrFail($id);
        $this->form->setDevelopTask($task);
        $this->dispatch('openEditModal');
    }

    public function save()
    {
        $this->form->update();
        $this->dispatch('updatedDevelopTask');
        $this->dispatch('success', message: 'Data berhasil diubah!');
        $this->dispatch('closeModal');
    }
};
?>

<div>
    <div wire:ignore.self class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Tambah Tahap Pengembangan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5">
                    <form class="row g-3" wire:submit.prevent='save'>
                        <div class="col-12">
                            <label class="form-label">Tipe Pengembangan</label>
                            <select wire:model="form.type_development"
                                class="form-select @error('form.type_development') is-invalid @enderror">
                                <option value="">Tipe Pengembangan</option>
                                <option value="aplikasi_baru">Aplikasi Baru</option>
                                <option value="peningkatan">Peningkatan</option>
                            </select>
                            @error('form.type_development')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="task" class="form-label">Nama Tahapan</label>
                            <input type="text" class="form-control @error('form.task_name') is-invalid @enderror"
                                id="task" wire:model='form.task_name'>
                            @error('form.task_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="role_id" class="form-label">Pilih orang yang memproses tahapan ini</label>
                            <select wire:model='form.role_id' id="role_id" disabled
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
                            <label for="sort_order" class="form-label">Urutan Tahap </label>
                            <input type="number" class="form-control @error('form.sort_order') is-invalid @enderror"
                                id="sort_order" wire:model='form.sort_order'>
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
                                Update</button>
                        </div>
                    </form><!-- Vertical Form -->
                </div>
            </div>
        </div>
    </div>
</div>