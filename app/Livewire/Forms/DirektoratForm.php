<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Direktorat;
use Livewire\Attributes\Validate;

class DirektoratForm extends Form
{
    public $name = '';
    public ?int $id = null;
    
    protected function rules()
    {
        return ['name' => 'required|min:5|max:255',];
    }

    protected function validationAttributes()
    {
        return  ['name' => 'Direktorat'];
    }

    protected function messages()
    {
        return [
            'name.required' => ':attribute wajib diisi',
            'name.min' => ':attribute minimal :min karakter',
            'name.max' => ':attribute maximal :max karakter',
        ];
    }

    public function store()
    {
        $this->validate();
        
        Direktorat::create([
            'name' => $this->name
        ]);

        $this->reset();
    }

    public function setDirektorat(Direktorat $direktorat)
    {
        $this->id = $direktorat->id;
        $this->name = $direktorat->name;
    }
    
    public function update()
    {
        $this->validate();
        
        $direktorat = Direktorat::findOrFail($this->id);

        $direktorat->update([
            'name' => $this->name
        ]);

        $this->reset();
    }
}
