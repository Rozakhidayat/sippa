<?php

use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
{
    #[On('updatedProfileUser')]
    #[On('updatedDeleteAvatar')]
    public function refresh()
    {
        
    }
};
?>

<div>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><span>Dashboard</span></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
        </div>

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                            @if (Auth::user()->avatars)
                            <img src="{{ asset('storage/' . Auth::user()->avatars) }}" alt="Profile"
                                class="rounded-circle" width="120">
                            @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                                style="width: 120px; height: 120px; font-size: 2.5rem;">
                                {{ Auth::user()->initial_name }}
                            </div>
                            @endif
                            <h2>{{ Auth::user()->name }}</h2>
                            <h3>{{ Auth::user()->role->name }}</h3>
                        </div>
                    </div>

                </div>

                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Overview</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit
                                        Profile</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-change-password">Change Password</button>
                                </li>

                            </ul>
                            <div class="tab-content pt-2">

                                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                    <h5 class="card-title">Profile Details</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                        <div class="col-lg-9 col-md-8">{{ Auth::user()->name }}</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">No.Badge</div>
                                        <div class="col-lg-9 col-md-8">{{ Auth::user()->no_badge }}</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Departement</div>
                                        <div class="col-lg-9 col-md-8">{{ Auth::user()->departement->name }}</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Job</div>
                                        <div class="col-lg-9 col-md-8">{{ Auth::user()->role->name}}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Email</div>
                                        <div class="col-lg-9 col-md-8">{{ Auth::user()->email }}</div>
                                    </div>

                                </div>

                                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                    <!-- Profile Edit Form -->
                                    <livewire:profile.edit_profile />
                                </div>


                                <div class="tab-pane fade pt-3" id="profile-change-password">
                                    <!-- Change Password Form -->
                                    <livewire:profile.edit_password />
                                </div>

                            </div><!-- End Bordered Tabs -->

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main><!-- End #main -->
    <x-alert />
</div>