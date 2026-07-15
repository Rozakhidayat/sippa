<?php

use App\Models\Theme;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\ThemeForm;

new class extends Component
{
    public ThemeForm $form;

    #[On('editTheme')]
    public function load(int $id)
    {
        $theme = Theme::findOrFail($id);
        
        $this->form->setData($theme);
        $this->dispatch('openEditModal');
    }

    public function save()
    {
        $this->form->update();
        $this->dispatch('success', message: 'Data berhasil diubah!');
        $this->dispatch('closeModal');
        $this->dispatch('editedTheme');
    }
};
?>
<div>
    <div wire:ignore.self class="modal fade" id="edit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fw-bold">Edit tema aplikasi</h1>
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
                                container: '#edit',
                                onSelect: ({date}) => {
                                    if (!date) return;
                                    this.thnAwal = date.getFullYear(); 
                                    if (this.pickerAkhir) {
                                        this.pickerAkhir.update({
                                            minDate: new Date(this.thnAwal, 0, 1),
                                            selectedDates: this.thnAkhir ? [new Date(this.thnAkhir, 0, 1)] : []
                                        });
                                    }
                                }
                            });
        
                            this.pickerAkhir = new AirDatepicker(this.$refs.airYearAkhir, {
                                view: 'years',
                                minView: 'years',
                                dateFormat: 'yyyy',
                                autoClose: true,
                                container: '#edit',
                                onSelect: ({date}) => {
                                    if (!date) return;
                                    this.thnAkhir = date.getFullYear();
                                }
                            });

                            this.$watch('thnAwal', val => {
                                if (val && this.pickerAwal) {
                                    this.pickerAwal.selectDate(new Date(val, 0, 1), {silent: true});
                                    this.pickerAkhir.update({ minDate: new Date(val, 0, 1) });
                                }
                            });

                            this.$watch('thnAkhir', val => {
                                if (val && this.pickerAkhir) {
                                    this.pickerAkhir.selectDate(new Date(val, 0, 1), {silent: true});
                                }
                            });
                        }
                    }" x-init="initPickers()">

                    <form class="row g-3" wire:submit.prevent='save'>
                        <div class="col-12">
                            <label class="form-label">Kategori</label>
                            <select wire:model='form.category'
                                class="form-select @error('form.category') is-invalid @enderror">
                                <option value="">Pilih Categori</option>
                                <option value="Cost Leadership">Cost Leadership</option>
                                <option value="New Revenue">New Revenue</option>
                                <option value="World Class Company">World Class Company</option>
                            </select>
                            @error('form.category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tema</label>
                            <input type="text" class="form-control @error('form.item') is-invalid @enderror"
                                wire:model='form.item'>
                        </div>

                        <div class="col-12">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small fw-semibold text-secondary">Tahun Awal</label>
                                    <div wire:ignore>
                                        <input type="text" x-ref="airYearAwal" readonly
                                            class="form-control bg-white text-center" placeholder="Pilih Tahun"
                                            :value="thnAwal" style="cursor: pointer">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-semibold text-secondary">Tahun Akhir</label>
                                    <div wire:ignore>
                                        <input type="text" x-ref="airYearAkhir" readonly
                                            :value="thnAkhir" class="form-control bg-white text-center"
                                            placeholder="Pilih Tahun" style="cursor: pointer">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success" wire:loading.attr='disabled'>Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>