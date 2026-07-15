<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            @if(auth()->user()->role->name === 'Manajer TI')
            <li class="nav-item">
                <a class="nav-link nav-link {{ request()->is('manajerti/dashboard*') ? '' : 'collapsed' }}" href="/manajerti/dashboard"
                    wire:navigate>
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>

            </li>

            <li class="nav-heading">Data Master</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('direktorats*') ? '' : 'collapsed' }}" href="/direktorats"
                    wire:navigate>
                    <i class="bi bi-diagram-3"></i>
                    <span>Direktorats</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('kompartements*') ? '' : 'collapsed' }}" href="/kompartements"
                    wire:navigate>
                    <i class="bi bi-diagram-2"></i>
                    <span>Kompartements</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('departements*') ? '' : 'collapsed' }}" href="/departements"
                    wire:navigate>
                    <i class="bi bi-buildings"></i>
                    <span>Unit Kerja</span>
                </a>
            </li>

            {{-- <li class="nav-item">
                <a class="nav-link {{ request()->is('themes*') ? '' : 'collapsed' }}" href="/themes" wire:navigate>
                    <i class="bx bxs-component"></i>
                    <span>IT Master Plan</span>
                </a>
            </li> --}}


            <li class="nav-item">
                <a class="nav-link {{ request()->is('themes*') || request()->is('theme-categories*') ? '' : 'collapsed' }}"
                    data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                    <i class="bx bxs-component"></i>
                    <span>Tema Aplikasi</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="tables-nav"
                    class="nav-content collapse {{ request()->is('themes*') || request()->is('theme-categories*') ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">

                    <li>
                        <a class="{{ request()->is('themes*') ? 'active' : '' }}" href="/themes" wire:navigate>
                            <i class="bi bi-circle"></i>
                            <span>Tema</span>
                        </a>
                    </li>

                    <li>
                        <a class="{{ request()->is('theme-categories*') ? 'active' : '' }}" href="/theme-categories"
                            wire:navigate>
                            <i class="bi bi-circle"></i>
                            <span>Kategori</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('workflows*') ? '' : 'collapsed' }}" href="/workflows"
                    wire:navigate>
                    <i class="bi bi-ui-checks"></"></i>
                    <span>Tahapan Persetujuan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('development_task*') ? '' : 'collapsed' }}" href="/development_task"
                    wire:navigate>
                    <i class="bi bi-signpost-split"></"></i>
                    <span>Tahapan Pengembangan</span>
                </a>
            </li>


            <li class="nav-heading">Menu</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('ti/pengajuan*') ? '' : 'collapsed' }}" href="/ti/pengajuan"
                    wire:navigate>
                    <i class="bx ri-draft-line"></i>
                    <span>Data Pengajuan</span>
                </a>
            </li>

            <li class="nav-heading">User Management</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('users*') ? '' : 'collapsed' }}" href="/users" wire:navigate>
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('roles*') ? '' : 'collapsed' }}" href="/roles" wire:navigate>
                    <i class="bi bi-shield-check"></i>
                    <span>Role</span>
                </a>
            </li>

            <li class="nav-heading">Reports</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('laporan*') ? '' : 'collapsed' }}" href="/laporan" wire:navigate>
                    <i class="bx ri-file-chart-line"></i>
                    <span>Laporan</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->role->name === 'Staff')
            <li class="nav-item">
                <a class="nav-link nav-link {{ request()->is('dashboard*') ? '' : 'collapsed' }}"
                    href="/dashboard" wire:navigate>
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-heading">Menu</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('pengajuan*') ? '' : 'collapsed' }}" href="/pengajuan"
                    wire:navigate>
                    <i class="bx ri-draft-line"></i>
                    <span>Pengajuan</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->role->name === 'VP')
            <li class="nav-item">
                <a class="nav-link nav-link {{ request()->is('vp/dashboard*') ? '' : 'collapsed' }}"
                    href="/vp/dashboard" wire:navigate>
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-heading">Menu</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('vp/approval*') ? '' : 'collapsed' }}" href="/vp/approval"
                    wire:navigate>
                    <i class="bx ri-draft-line"></i>
                    <span>Persetujuan</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->role->name === 'SVP')
            <li class="nav-item">
                <a class="nav-link nav-link {{ request()->is('svp/dashboard*') ? '' : 'collapsed' }}"
                    href="/svp/dashboard" wire:navigate>
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-heading">Menu</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('svp/approval*') ? '' : 'collapsed' }}" href="/svp/approval"
                    wire:navigate>
                    <i class="bx ri-draft-line"></i>
                    <span>Persetujuan</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->role->name === 'Business Partner')
            <li class="nav-item">
                <a class="nav-link nav-link {{ request()->is('bp/dashboard*') ? '' : 'collapsed' }}"
                    href="/bp/dashboard" wire:navigate>
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-heading">Menu</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('bp/pengajuan*') ? '' : 'collapsed' }}" href="/bp/pengajuan"
                    wire:navigate>
                    <i class="bx ri-draft-line"></i>
                    <span>Analisis Pengajuan</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->role->name === 'Enterprise Architect')
            <li class="nav-item">
                <a class="nav-link nav-link {{ request()->is('ea/dashboard*') ? '' : 'collapsed' }}"
                    href="/ea/dashboard" wire:navigate>
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-heading">Menu</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('ea/pengajuan*') ? '' : 'collapsed' }}" href="/ea/pengajuan"
                    wire:navigate>
                    <i class="bx ri-draft-line"></i>
                    <span>Review BRD</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->role->name === 'Developer')
            <li class="nav-item">
                <a class="nav-link nav-link {{ request()->is('developer/dashboard*') ? '' : 'collapsed' }}"
                    href="/developer/dashboard" wire:navigate>
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-heading">Menu</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('developer/pengajuan*') ? '' : 'collapsed' }}"
                    href="/developer/pengajuan" wire:navigate>
                    <i class="bi bi-code-slash"></i>
                    <span>Progres Development</span>
                </a>
            </li>
            @endif

            <li class="nav-heading">Settings</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('profile*') ? '' : 'collapsed' }}" href="/profile" wire:navigate>
                    <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
            </li>

    </aside>
</div>