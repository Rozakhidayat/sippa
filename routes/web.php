<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
});

Route::group(['middleware' => 'auth'], function(){
Route::livewire('/profile', 'profile.index');
});

Route::middleware(['auth', 'role:Manajer TI'])->group(function () {
    Route::livewire('/manajerti/dashboard', 'manajer-ti.dashboard.index')->name('manajerTI.dashboard');
    Route::livewire('/direktorats', 'manajer-ti.direktorat.index')->name('direktorats');
    Route::livewire('/kompartements', 'manajer-ti.kompartement.index');
    Route::livewire('/departements', 'manajer-ti.departemen.index');
    Route::livewire('/themes', 'manajer-ti.app-theme.index');
    Route::livewire('/theme-categories', 'manajer-ti.theme_category.index');
    Route::livewire('/roles', 'manajer-ti.role.index');
    Route::livewire('/users', 'manajer-ti.user.index');
    Route::livewire('/workflows', 'manajer-ti.workflow.index');
    Route::livewire('/development_task', 'manajer-ti.development_task.index');
    Route::livewire('/ti/pengajuan', 'manajer-ti.pengajuan.index')->name('ti.pengajuan.index');
    Route::livewire('/laporan', 'manajer-ti.laporan.index');
    Route::livewire('/ti/pengajuan/show/{no_ticket}', 'manajer-ti.pengajuan.show')->name('ti.pengajuan.show');
});

Route::middleware(['auth', 'role:Staff'])->group(function () {
    Route::livewire('/dashboard', 'staff.pengajuan.dashboard')->name('pengajuan.dashboard');
    Route::livewire('/pengajuan', 'staff.pengajuan.index')->name('pengajuan');
    Route::livewire('/pengajuan/create', 'staff.pengajuan.create');
    Route::livewire('/pengajuan/edit/{no_ticket}', 'staff.pengajuan.edit')->name('pengajuan.edit');
    Route::livewire('/pengajuan/show/{no_ticket}', 'staff.pengajuan.show')->name('pengajuan.show');
});

Route::middleware(['auth', 'role:VP'])->group(function () {
    Route::livewire('/vp/approval', 'vp.pengajuan.index')->name('vp.approval');
    Route::livewire('/vp/dashboard', 'vp.pengajuan.dashboard')->name('dashboard.vp');
    Route::livewire('/vp/approval/show/{no_ticket}', 'vp.pengajuan.show')->name('vp.approval.show');
});

Route::middleware(['auth', 'role:SVP'])->group(function () {
    Route::livewire('/svp/approval', 'svp.pengajuan.index')->name('svp.approval');
    Route::livewire('/svp/dashboard', 'svp.pengajuan.dashboard')->name('svp.dashboard');
    Route::livewire('/svp/approval/show/{no_ticket}', 'svp.pengajuan.show')->name('svp.approval.show');
});

Route::middleware(['auth', 'role:Business Partner'])->group(function () {
    Route::livewire('/bp/dashboard', 'bp.pengajuan.dashboard')->name('bp.dashboard');
    Route::livewire('/bp/pengajuan', 'bp.pengajuan.index')->name('bp.pengajuan');
    Route::livewire('/bp/pengajuan/show/{no_ticket}', 'bp.pengajuan.show')->name('bp.pengajuan.show');
});

Route::middleware(['auth', 'role:Enterprise Architect'])->group(function () {
    Route::livewire('/ea/dashboard', 'ea.pengajuan.dashboard')->name('ea.dashboard');
    Route::livewire('/ea/pengajuan', 'ea.pengajuan.index')->name('ea.pengajuan');
    Route::livewire('/ea/pengajuan/show/{no_ticket}', 'ea.pengajuan.show')->name('ea.pengajuan.show');
});

Route::middleware(['auth', 'role:Developer'])->group(function () {
    Route::livewire('/developer/pengajuan', 'developer.pengajuan.index')->name('developer.pengajuan');
    Route::livewire('/developer/dashboard', 'developer.dashboard.index')->name('developer.dashboard');
    Route::livewire('/developer/pengajuan/show/{no_ticket}', 'developer.pengajuan.show')->name('developer.pengajuan.show');
});
Route::group(['middleware' => 'guest'], function(){
Route::livewire('/login', 'auth.login')->name('login');
});
