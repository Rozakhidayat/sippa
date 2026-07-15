<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\themeCategory;
use App\Livewire\Forms\CategoryForm;

new class extends Component
{
    public CategoryForm $form;
    
    #[On('editCategory')]  
    public function load(int $id)
    {
        $category = themeCategory::findOrFail($id);
        $this->form->setCategory($category);
        $this->dispatch('openEditModal');
    }

    public function save()
    {
        $this->form->update();
        $this->dispatch('editedCategory');
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
                    <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Edit Data Kategori</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5">
                    <form class="row g-3" wire:submit.prevent='save'>
                        <div class="col-12">
                            <label for="category" class="form-label">Kategori</label>
                            <input type="text" class="form-control @error('form.name') is-invalid @enderror"
                                id="category" wire:model='form.name'>
                            @error('form.name')
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