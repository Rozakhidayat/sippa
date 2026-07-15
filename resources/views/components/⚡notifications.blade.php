<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    // Properti untuk menyimpan notifikasi
    public $notifications;

    // Menjalankan saat komponen dimuat
    public function mount()
    {
        $this->loadNotifications();
    }

    // Method untuk memuat notifikasi
    public function loadNotifications()
    {
        $this->notifications = Auth::user()->unreadNotifications;
    }

    public function markAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        // Refresh data setelah dibaca
        $this->loadNotifications();
        
        $this->dispatch('close-dropdown');
    }
};
?>

<div wire:init="loadNotifications" wire:poll.30s="loadNotifications">
    <li class="nav-item dropdown">
        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" id="notificationDropdown">
            <i class="bi bi-bell"></i>
            {{-- GUNAKAN VARIABEL $notifications --}}
            @if($notifications && $notifications->count() > 0)
            <span class="badge bg-primary badge-number">{{ $notifications->count() }}</span>
            @endif
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications"
            style="min-width: 300px; max-height: 400px; overflow-y: auto;" wire:ignore.self>

            <li class="dropdown-header">
                {{-- GUNAKAN VARIABEL $notifications --}}
                Kamu punya {{ $notifications ? $notifications->count() : 0 }} notifikasi baru
                <a href="#" class="ms-2" title="Lihat semua notifikasi">
                    <i class="bi bi-bell-fill text-primary" style="font-size: 1.1rem;"></i>
                </a>
            </li>

            {{-- GUNAKAN VARIABEL $notifications --}}
            @forelse ($notifications ?? [] as $notification)
            <li>
                <hr class="dropdown-divider">
            </li>
            <li class="notification-item">
                @php
                $msg = $notification->data['message'];
                $icon = 'bi-info-circle text-primary';
                if(str_contains($msg, 'disetujui')) $icon = 'bi-check-circle text-success';
                if(str_contains($msg, 'revisi')) $icon = 'bi-exclamation-circle text-warning';
                if(str_contains($msg, 'ditolak')) $icon = 'bi-x-circle text-danger';
                @endphp
                <i class="{{ $icon }}"></i>
                <div>
                    <h4>{{ $notification->data['title'] }}</h4>
                    <p>{{ $msg }}</p>
                    <p>{{ $notification->created_at->diffForHumans() }}</p>
                </div>
            </li>
            @empty
            <li class="notification-item">
                <div class="text-center w-100 py-3">Tidak ada notifikasi baru</div>
            </li>
            @endforelse

            <li>
                <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
                <button type="button" wire:click="markAsRead"
                    class="btn btn-link text-decoration-none w-100 text-center">
                    Tandai sudah dibaca
                </button>
            </li>
        </ul>
    </li>
</div>