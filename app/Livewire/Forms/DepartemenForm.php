<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Departement;
use Livewire\Attributes\Validate;

class DepartemenForm extends Form
{
    public $name = '';
    public $is_active = true;
    public ?int $kompartement_id = null;
    public ?int $id = null;
    

    protected function rules()
    {
        return [
            'name' => 'required|min:5|max:255',
            'kompartement_id' => 'required|exists:kompartements,id',
        ];
    }

    protected function validationAttributes()
    {
        return  [
            'name' => 'Unit Kerja',
            'kompartement_id' => 'Kompartement',
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => ':attribute wajib diisi',
            'name.min' => ':attribute minimal :min karakter',
            'name.max' => ':attribute maximal :max karakter',
            'kompartement_id.required' => 'Silahkan pilih :attribute',
        ];
    }

    public function store()
    {
        $this->validate();
        
        Departement::create([
            'name' => $this->name,
            'kompartement_id' => $this->kompartement_id,
            'is_active' => $this->is_active
        ]);

        $this->reset();
    }

    public function setDepartement(Departement $departement)
    {
        $this->id = $departement->id;
        $this->name = $departement->name;
        $this->kompartement_id = $departement->kompartement_id;
    }

    public function update()
    {
        $this->validate();
        
        $departement = Departement::findOrFail($this->id);

        $departement->update([
            'name' => $this->name,
            'kompartement_id' => $this->kompartement_id,
            'is_active' => $this->is_active
        ]);

        $this->reset();
    }
}
