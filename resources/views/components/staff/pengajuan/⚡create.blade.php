<?php

use App\Models\Theme;
use Livewire\Component;
use App\Models\Departement;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Forms\SubmissionForm;


new class extends Component
{
    public SubmissionForm $form;
    
    public function mount()
    {
        $user = Auth::user();
        $this->form->user_name = $user->name;
        $this->form->departement_id = $user->departement_id;
        $this->form->themes = [];
        $this->form->submission_date = now()->format('Y-m-d');
    }

    public function save() {
        $this->form->store();
        
        session()->flash('success', 'Berhail menambahkan pengajuan');
        $this->dispatch('createdSubmission');
        return $this->redirect('/pengajuan');
    }

    public function render()
    {
        $themesByCategory = Theme::query()
            ->where('is_active', true)
            ->get()
            ->groupBy(function ($theme) {
                return $theme->category ? $theme->category->name : 'TANPA KATEGORI';
        });
        
        return $this->view([
            'themesByCategory' => $themesByCategory,
            'departements' => Departement::pluck('name', 'id'),
        ]);
    }
};
?>

<div>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Formulir Pengajuan</h1>
        </div>

        <section class="section">
            <form wire:submit.prevent='save'>
                <div class="card border-0 shadow-sm p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Tanggal</label>
                            <input wire:model='form.submission_date' type="date"
                                class="form-control form-control-sm @error('form.submission_date') is-invalid @enderror">
                            @error('form.submission_date')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Nama Pemohon</label>
                            <input readonly wire:model='form.user_name' type="text"
                                class="form-control bg-light form-control-sm @error('form.user_name') is-invalid @enderror ">
                            @error('form.user_name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        {{-- Departemen --}}
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Departemen</label>
                            <select wire:model="form.departement_id"
                                class="form-select form-select-sm bg-light @error('form.departement_id') is-invalid @enderror"
                                disabled>
                                <option value="">Pilih Departemen</option>
                                @foreach ($departements as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('form.departement_id')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>


                        {{-- Jenis Pengembangan --}}
                        <div class="col-12 mt-3 ">
                            <label class="form-label small fw-bold d-block">Jenis Pengembangan</label>
                            <div class="form-check form-check-inline">
                                <input wire:model="form.type_development"
                                    class="form-check-input @error('form.type_development') is-invalid @enderror"
                                    type="radio" value="aplikasi_baru" id="aplikai_baru">
                                <label class="form-check-label small" for="aplikasi_baru">Aplikasi Baru</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input wire:model="form.type_development"
                                    class="form-check-input @error('form.type_development') is-invalid @enderror"
                                    type="radio" value="peningkatan" id="peningkatan">
                                <label class="form-check-label small" for="peningkatan">Peningkatan Aplikasi</label>
                            </div>
                            @error('form.type_development')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        {{-- Nama Aplikasi --}}
                        <div class="col-6 mt-3">
                            <label class="form-label small fw-bold">Nama Aplikasi</label>

                            <input wire:model="form.application_name" type="text"
                                class="form-control form-control-sm @error('form.application_name') is-invalid @enderror"
                                placeholder="Masukan nama aplikasi anda">
                            @error('form.application_name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12 mt-3">
                            <label class="form-label small fw-bold">Latar Belakang</label>
                            <textarea wire:model="form.background"
                                class="form-control @error('form.background') is-invalid @enderror" rows="3"
                                placeholder="Jelaskan latar belakang secara singkat">
                            </textarea>
                            @error('form.background')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12 mt-3">
                            <label class="form-label small fw-bold">Lingkup Aplikasi</label>
                            <textarea wire:model="form.application_scope"
                                class="form-control @error('form.application_scope') is-invalid @enderror"
                                placeholder="Contoh: Dept Ti sebagai pengelola dapat melakukan 'create, update, delete'"
                                rows="3"></textarea>
                            @error('form.application_scope')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label small fw-bold">Cost Benefit</label>
                            <textarea wire:model="form.cost_benefit"
                                class="form-control @error('form.cost_benefit') is-invalid @enderror"
                                placeholder="Masukkan estimasi biaya dan manfaat" rows="3"></textarea>
                            @error('form.cost_benefit')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label small fw-bold">Resiko Jika Tidak Dibuat</label>
                            <textarea wire:model="form.risk"
                                class="form-control @error('form.risk') is-invalid @enderror" rows="3"
                                placeholder="Jelaskan secara singkat resiko jika aplikasi tidak dibuat"></textarea>
                            @error('form.risk')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        {{-- ITMP Themes --}}
                        <div class="col-12 mt-4">
                            <label class="fw-bold small mb-2">Kesesuaian IT Master Plan</label>
                            <div class="row ">
                                @forelse ($themesByCategory as $category => $items)
                                <div class="col-md-6 mb-3">
                                    <h6 class="small fw-bold text-primary">{{ strtoupper($category) }}</h6>
                                    @foreach($items as $theme)
                                    <div class="form-check small">
                                        <input class="form-check-input @error('form.themes') is-invalid @enderror"
                                            type="checkbox" wire:model="form.themes" value="{{ $theme->id }}"
                                            id="theme{{ $theme->id }}">
                                        <label class="form-check-label" for="theme{{ $theme->id }}">{{ $theme->item
                                            }}</label>
                                    </div>
                                    @endforeach
                                </div>
                                @empty
                                <div class="text-sm">Data tidak terdia</div>
                                @endforelse
                            </div>
                            @error('form.themes')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                            <a href='/pengajuan' wire:navigate class="btn btn-sm btn-danger">Batal</a>
                            <button type="submit" class="btn btn-sm btn-outline-success" wire:loading.attr='disabled'>
                                <span wire:loading wire:target='save' class="spinner-border spinner-border-sm"></span>
                                <i class="bi bi-send"></i> Kirim Pengajuan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>
</div>