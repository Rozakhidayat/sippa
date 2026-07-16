<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Forms\UserForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

new class extends Component
{
    use WithFileUploads;

    public UserForm $form;

    public mixed $avatars = null;

    public function mount()
    {
        $user = Auth::user();
        $this->form->setData($user);
    }
    
    public function update()
    {
        $this->validate([
            'avatars' => 'nullable|image|max:2000',
        ]);

        $this->form->update();

        if($this->avatars){
            $user = Auth::user();
            if ($user->avatars && Storage::disk((config('filesystems.default_public_disk')))->exists($user->avatars)) {
                Storage::disk(config('filesystems.default_public_disk'))->delete($user->avatars);
            }
            
            $path = $this->avatars->store('avatars', (config('filesystems.default_public_disk')));

            $user->update([
            'avatars' => $path
        ]);
        $this->reset('avatars');
        }
        
        $this->mount();
        Session::flash('success', 'Profil berhasil diperbarui!');
        $this->dispatch('updatedProfileUser');
        return $this->redirect('/profile', navigate: true);
    }

    public function deleteAvatar()
    {
        $user = Auth::user();
        if ($user->avatars) {
            Storage::disk((config('filesystems.default_public_disk')))->delete($user->avatars);
        }
        $user->update(['avatars' => null]);
        
        $this->dispatch('updatedDeleteAvatar');
        Session::flash('success', 'Foto profil berhasil dihapus!');
        return $this->redirect('/profile', navigate: true);
    }

};
?>

<div>
    <form wire:submit.prevent='update'>
        <div class="row mb-3">
            <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile
                Image</label>
            <div class="col-md-8 col-lg-9">
    
                <div wire:loading.flex wire:target="avatars" class="justify-content-center align-items-center border rounded"
                    style="width: 120px; height: 120px; background-color: #f1f3f5;">
    
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
    
                </div>
    
                <div wire:loading.remove wire:target="avatars">
                    @if ($avatars)
                    <img src="{{ $avatars->temporaryUrl() }}" alt="Profile">
                    @endif
    
                    <div class="pt-2">
                        <input wire:model='avatars' class="form-control mt-1 @error('avatars') is-invalid @enderror"
                            type="file" id="formFile" accept="image/png, image/jpg, image/jpeg">
                        @error('avatars')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
    
            </div>
        </div>
    
        <div class="row mb-3">
            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full
                Name</label>
            <div class="col-md-8 col-lg-9">
                <input wire:model='form.name' name="fullName" type="text"
                    class="form-control @error('form.name') is-invalid @enderror" id="fullName">
                @error('form.name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    
        <div class="row mb-3">
            <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
            <div class="col-md-8 col-lg-9">
                <input wire:model='form.email' name="email" type="email"
                    class="form-control @error('form.email') is-invalid @enderror" id="Email">
                @error('form.email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    
        <div class="text-center">
            @if (Auth::user()->avatars)
            <button type="button" class="btn btn-danger" x-on:click="
                        Swal.fire({
                            title: 'Hapus Foto Profil?',
                            text: 'Foto kamu akan dihapus secara permanen!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.deleteAvatar()
                            }
                        })">
                <i class="bi bi-trash"></i> Delete Profile
            </button>
            @endif
    
            <button type="submit" class="btn btn-primary" wire:loading.attr='disabled'>
                <span wire:loading wire:target='update' class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span>Save Changes
            </button>
        </div>
    </form>
</div>