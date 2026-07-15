<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email;
    public string $password;
    
    /**
     * login
     *
     * @return void
     */
    public function login()
    {
        $this->validate([
            'email'     => 'required|email',
            'password'  => 'required'
        ],
        [
            'email.required' => 'Email tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
            'email.email' => 'Format email tidak valid'
        ]
        );

        if(Auth::attempt(['email' => $this->email, 'password'=> $this->password])) {
            request()->session()->regenerate();
            $user = Auth::user();

            if ($user->role->name === 'Manajer TI') {
            return redirect()->route('manajerTI.dashboard');
            }
            
            if ($user->role->name === 'Staff') {
                return redirect()->route('pengajuan.dashboard');
            }

            if ($user->role->name === 'VP') {
                return redirect()->route('dashboard.vp');
            }
            
            if ($user->role->name === 'SVP') {
                return redirect()->route('svp.dashboard');
            }
            
            if ($user->role->name === 'Business Partner') {
                return redirect()->route('bp.dashboard');
            }
            
            if ($user->role->name === 'Enterprise Architect') {
                return redirect()->route('ea.dashboard');
            }
            
            if ($user->role->name === 'Developer') {
                return redirect()->route('developer.dashboard');
            }
            
            
        } else{
            $this->addError('email', 'Email atau Password Anda salah!');
        }
    }
};
?>

<div>
    <main>
        <div class="container">
            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <div class="logo d-flex align-items-center w-auto">
                                    <img src="assets/img/favicon.ico" alt="">
                                    <span class="d-none d-lg-block">SIPPA</span>
                                </div>
                            </div><!-- End Logo -->

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Login to Apps</h5>
                                        <p class="text-center small">Enter your email & password to login</p>
                                    </div>

                                    <form wire:submit.prevent='login' class="row g-3 needs-validation" novalidate>

                                        <div class="col-12">
                                            <label for="email" class="form-label">Email</label>
                                            <div class="input-group has-validation">
                                                <input type="email" wire:model.lazy='email'
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="email">
                                                @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <input type="password" wire:model.lazy='password'
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="password">
                                            @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary w-100" type="submit"
                                                wire:loading.attr='disabled'>
                                                <span wire:loading wire:target='login'
                                                    class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Login
                                            </button>
                                        </div>
                                        <div class="col-12">
                                            <a class='small' href="/">Back to home</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main>
</div>