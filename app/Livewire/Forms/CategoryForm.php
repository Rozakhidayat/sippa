<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\themeCategory;
use Livewire\Attributes\Validate;

class CategoryForm extends Form
{
    public $name = '';
    public ?int $id = null;

    protected function rules()
    {
        return ['name' => 'required|min:5|max:255',];
    }

    protected function validationAttributes()
    {
        return  ['name' => 'Kategori'];
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
        
        themeCategory::create([
            'name' => $this->name
        ]);

        $this->reset();
    }

    public function setCategory(themeCategory $category)
    {
        $this->id = $category->id;
        $this->name = $category->name;
    }

    public function update()
    {
        $this->validate();
        
        $category = themeCategory::findOrFail($this->id);

        $category->update([
            'name' => $this->name
        ]);

        $this->reset();
    }

}
