<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Role;
use Livewire\Attributes\Validate;

class RoleForm extends Form
{
    public $name = '';
    public ?int $id = null;

    protected function rules()
    {
        return [
            'name' => 'required|unique:roles,name,'. $this->id
        ];
    }

    protected function validationAttributes() 
    {
        return [
            'name' => 'Role',
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => ':attribute wajib diisi',
            'name.unique' => ':attribute sudah tersedia'
        ];
    }

    public function store()
    {
        $this->validate();
        
        Role::create([
            'name' => $this->name
        ]);

        $this->reset();
    }

    public function setRole(Role $role)
    {
        $this->id = $role->id;
        $this->name = $role->name;
    }

    public function update()
    {
        $this->validate();
        
        $role = Role::findOrFail($this->id);

        $role->update([
            'name' => $this->name
        ]);

        $this->reset();
    }
}
