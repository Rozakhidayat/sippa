<?php

use Livewire\Component;
use App\Models\themeCategory;
use App\Livewire\Forms\ThemeForm;
use Livewire\Attributes\Computed;

new class extends Component
{
    public ThemeForm $form;

    #[Computed()]
    public function categories()
    {
        return themeCategory::pluck('name', 'id');
    }
    
    public function save()
    {
        $this->form->store();
        $this->dispatch('createdTheme');
        $this->dispatch('success', message: 'Data berhasil ditambahkan!');
        $this->dispatch('closeModal');
    }
};
?>

<div>
    <div wire:ignore.self class="modal fade" id="create" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fw-bold">Tambah tema aplikasi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pb-5" x-data="{
                        thnAwal: @entangle('form.periode_awal'),
                        thnAkhir: @entangle('form.periode_akhir'),
                        pickerAwal: null,
                        pickerAkhir: null,

                        initPickers() {
                            this.pickerAwal = new AirDatepicker(this.$refs.airYearAwal, {
                                view: 'years',
                                minView: 'years',
                                dateFormat: 'yyyy',
                                autoClose: true,
                                container: '#create',
                                selectedDates: this.thnAwal ? [new Date(this.thnAwal, 0, 1)] : [],
                                onSelect: ({date}) => {
                                    if (!date) return;
                                    this.thnAwal = date.getFullYear(); 

                                    if (this.pickerAkhir) {
                                        this.pickerAkhir.update({
                                            minDate: new Date(this.thnAwal, 0, 1)
                                        });
                                    }
                                }
                            });

                            this.pickerAkhir = new AirDatepicker(this.$refs.airYearAkhir, {
                                view: 'years',
                                minView: 'years',
                                dateFormat: 'yyyy',
                                autoClose: true,
                                container: '#create',
                                selectedDates: this.thnAkhir ? [new Date(this.thnAkhir, 0, 1)] : [],
                                minDate: this.thnAwal ? new Date(this.thnAwal, 0, 1) : false,
                                onSelect: ({date}) => {
                                    if (!date) return;
                                    this.thnAkhir = date.getFullYear();
                                }
                            });
                        }
                    }" x-init="initPickers()">

                    <form class="row g-3" wire:submit.prevent='save'>

                        <!-- KATEGORI -->
                        <div class="col-12">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select wire:model='form.category_id' id="category_id"
                                class="form-select @error('form.category_id') is-invalid @enderror">
                                <option value="">Pilih Kategori</option>
                                @foreach ($this->categories as $id => $name)
                                <option value="{{ $id }}">{{$name}}</option>
                                @endforeach
                            </select>
                            @error('form.category_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- TEMA -->
                        <div class="col-12">
                            <label for="item" class="form-label">Tema</label>
                            <input type="text" class="form-control @error('form.item') is-invalid @enderror" id="item"
                                wire:model='form.item'>
                            @error('form.item') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <div class="row g-2">
                                <div class="col-md-6 text-start">
                                    <label class="form-label small fw-semibold text-secondary">Tahun Awal</label>
                                    <div wire:ignore>

                                        <input type="text" x-ref="airYearAwal" x-model="thnAwal" readonly
                                            class="form-control bg-white text-center @error('form.periode_awal') is-invalid @enderror"
                                            style="cursor: pointer;" placeholder="Pilih Tahun">
                                    </div>
                                    @error('form.periode_awal') <span class="text-danger small d-block mt-1">{{ $message
                                        }}</span> @enderror
                                </div>

                                <div class="col-md-6 text-start">
                                    <label class="form-label small fw-semibold text-secondary">Tahun Akhir</label>
                                    <div wire:ignore>

                                        <input type="text" x-ref="airYearAkhir" x-model="thnAkhir" readonly
                                            class="form-control bg-white text-center @error('form.periode_akhir') is-invalid @enderror"
                                            style="border-radius: 0.5rem; padding: 0.6rem; cursor: pointer;"
                                            placeholder="Pilih Tahun">
                                    </div>
                                    @error('form.periode_akhir') <span class="text-danger small d-block mt-1">{{
                                        $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- TOMBOL ACTIONS -->
                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success" wire:loading.attr='disabled'>
                                <span wire:loading wire:target='save' class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true"></span>
                                Kirim
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>