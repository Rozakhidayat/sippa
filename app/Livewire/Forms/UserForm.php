<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;

class UserForm extends Form
{
    public ?int $id = null;
    public string $name = '';
    public ?int $no_badge = null;
    public string $email = '';
    public ?int $departement_id = null;
    public ?int $role_id = null;
    public ?string $password = '';
    public ?string $password_confirmation = '';

    protected function rules()
    {
        $rules = [
            'name' => 'required|string',
            'no_badge' => 'required|min:5|unique:users,no_badge,'. $this->id,
            'email' => 'required|email|unique:users,email,'. $this->id,
            'departement_id' => 'required|exists:departements,id',
            'role_id' => 'required|exists:roles,id',
        ];
        
        if ($this->id) {
        $rules['password'] = 'nullable|min:5|confirmed';
        } else {
            $rules['password'] = 'required|min:5|confirmed';
        }

        return $rules;
    }

    protected function validationAttributes() 
    {
        return [
            'name' => 'Nama',
            'no_badge' => 'Nomor Badge',
            'email' => 'Email',
            'departement_id' => 'Departement',
            'role_id' => 'Role',
            'password' => 'Password'
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => ':attribute wajib diisi',
            'no_badge.required' => ':attribute wajib diisi',
            'email.required' => ':attribute wajib diisi',
            'departement_id.required' => 'Silahkan pilih :attribute',
            'role_id.required' => 'Silahkan pilih :attribute ',
            'no_badge.unique'=> ':attribute sudah tersedia',
            'email.unique' => ':attribute sudah terdaftar',
            'password.required' => ':attribute wajib diisi',
            'password.confirmed' => ':attribute tidak cocok',
            'no_badge.min' => ':attribute minimal :min karakter',
            'password.min' => ':attribute minimal :min karakter'
        ];
    }

    public function store()
    {
        $this->validate();

            User::create([
            'name' => $this->name,
            'no_badge' => $this->no_badge,
            'email' => $this->email,
            'departement_id' => $this->departement_id,
            'role_id' => $this->role_id,
            'password' => Hash::make($this->password),
            
        ]);

        $this->reset();
    }

    public function setData(User $user) 
    {
        $this->id = $user->id;
        $this->name = $user->name;
        $this->no_badge = $user->no_badge;
        $this->email = $user->email;
        $this->departement_id = $user->departement_id;
        $this->role_id = $user->role_id;
    }

    public function update()
    {
        $this->validate();
        
        $user = User::findOrFail($this->id);

        $data = [
            'name' => $this->name,
            'no_badge' => $this->no_badge,
            'email' => $this->email,
            'departement_id' => $this->departement_id,
            'role_id' => $this->role_id
        ];

        if (!empty($this->password)) {
        $data['password'] = Hash::make($this->password);
        }
        
        $user->update($data);
        
        $this->reset();
    }
}
