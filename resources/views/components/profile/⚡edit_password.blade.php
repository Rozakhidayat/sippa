<?php


use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

new class extends Component
{
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPassword_confirmation = '';

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => 'required|string',
            'newPassword' => 'required|confirmed|min:5'
        ]);
        

        $user = Auth::user();
        
        if (!Hash::check($this->currentPassword, $user->password)){
            throw ValidationException::withMessages([
                'currentPassword' => 'Password saat ini tidak sesuai'
            ]);
        }

        $user->password = Hash::make($this->newPassword);
        $user->save();

        $this->reset(['currentPassword', 'newPassword', 'newPassword_confirmation']);
        Session::flash('success', 'Password berhasil diperbarui!');
        return $this->redirect('/profile', navigate: true);
    }
};
?>

<div>
    <form wire:submit.prevent='updatePassword'>
        <div class="row mb-3">
            <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current
                Password</label>
            <div class="col-md-8 col-lg-9">
                <input wire:model='currentPassword' name="password" type="password"
                    class="form-control @error('currentPassword') is-invalid @enderror" id="currentPassword"
                    autocomplete="new-password">
                @error('currentPassword')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New
                Password</label>
            <div class="col-md-8 col-lg-9">
                <input wire:model='newPassword' name="newpassword" type="password"
                    class="form-control @error('newPassword') is-invalid @enderror" id="newPassword">
                @error('newPassword')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter
                New
                Password</label>
            <div class="col-md-8 col-lg-9">
                <input wire:model='newPassword_confirmation' name="newPassword_confirmation" type="password"
                    class="form-control @error('newPassword_confirmation') is-invalid @enderror " id="renewPassword">
                @error('newPassword_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">
                <span wire:loading wire:target='updatePassword' class="spinner-border spinner-border-sm" role="status"
                    aria-hidden="true"></span> Change Password
            </button>
        </div>
    </form>
</div>