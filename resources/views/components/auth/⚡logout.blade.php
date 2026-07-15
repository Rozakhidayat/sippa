<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
};
?>

<div>
    <li>
        <a class="dropdown-item d-flex align-items-center" href="#" wire:click.prevent="logout">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign Out</span>
        </a>
    </li>
</div>