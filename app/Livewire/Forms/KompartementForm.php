<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Kompartement;
use Livewire\Attributes\Validate;

class KompartementForm extends Form
{
    public $name = '';
    public ?int $direktorat_id = null;
    public ?int $id = null;

    protected function rules()
    {
        return [
            'name' => 'required|min:5|max:255',
            'direktorat_id' => 'required|exists:direktorats,id',
        ];
    }

    protected function validationAttributes()
    {
        return  [
            'name' => 'Kompartement',
            'direktorat_id' => 'Direktorat',
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => ':attribute wajib diisi',
            'name.min' => ':attribute minimal :min karakter',
            'name.max' => ':attribute maximal :max karakter',
            'direktorat_id.required' => 'Silahkan pilih :attribute',
        ];
    }

    public function store()
    {
        $this->validate();
        
        Kompartement::create([
            'name' => $this->name,
            'direktorat_id' => $this->direktorat_id
        ]);

        $this->reset();
    }

    public function setKompartement(Kompartement $kompartement)
    {
        $this->id = $kompartement->id;
        $this->name = $kompartement->name;
        $this->direktorat_id = $kompartement->direktorat_id;
    }

    public function update()
    {
        $this->validate();
        
        $kompartement = Kompartement::findOrFail($this->id);

        $kompartement->update([
            'name' => $this->name,
            'direktorat_id' => $this->direktorat_id
        ]);

        $this->reset();
    }
}
