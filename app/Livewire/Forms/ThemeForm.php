<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Theme;
use Livewire\Attributes\Validate;

class ThemeForm extends Form
{
    public ?int $id = null;
    public ?int $category_id = null ;
    public $item = '';
    public $periode_awal = ''; 
    public $periode_akhir = ''; 
    public $is_active = true;

    protected function rules()
    {
        return [
            'category_id'      => 'required',
            'item'          => 'required|min:3|max:255',
            'periode_awal'  => 'required|numeric|digits:4',
            'periode_akhir' => 'required|numeric|digits:4|gte:periode_awal',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'category_id'      => 'Kategori',
            'item'          => 'Tema',
            'periode_awal'  => 'Periode awal',
            'periode_akhir' => 'Periode akhir',
        ];
    }

    protected function messages()
    {
        return [
            '*.required' => ':attribute wajib diisi.',
            '*.digits'   => ':attribute harus berupa 4 digit angka tahun.',
            'periode_akhir.gte' => ':attribute tidak boleh kurang dari periode awal.',
        ];
    }

    public function store()
    {
        $this->validate();

        $periodeGabungan = $this->periode_awal . '-' . $this->periode_akhir;

        Theme::create([
            'category_id' => $this->category_id,
            'item'     => $this->item,
            'periode'  => $periodeGabungan, 
            'is_active' => $this->is_active
        ]);

        $this->reset();
    }

    public function setData(Theme $theme) : void
    {
        $this->id = $theme->id;
        $this->category_id = $theme->category_id;
        $this->item = $theme->item;
        $this->is_active = $theme->is_active;
        
        if ($theme->periode && str_contains($theme->periode, '-')) {
            [$this->periode_awal, $this->periode_akhir] = explode('-', $theme->periode);
        }
    }

    public function update()
    {
        $this->validate();

        $theme = Theme::findOrFail($this->id);
        
        $periodeGabungan = $this->periode_awal . '-' . $this->periode_akhir;

        $theme->update([
            'category_id'  => $this->category_id,
            'item'      => $this->item,
            'periode'   => $periodeGabungan,
            'is_active' => $this->is_active
        ]);

        $this->reset([
        'id',
        'category_id',
        'item',
        'periode_awal',
        'periode_akhir',
        ]);
    }
}
