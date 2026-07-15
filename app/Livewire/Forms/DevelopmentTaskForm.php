<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\DevelopmentTask;
use Livewire\Attributes\Validate;

class DevelopmentTaskForm extends Form
{
    public ?int $id = null;
    public string $type_development = '';
    public ?string $task_name = '';
    public ?string $sort_order = null;
    public ?int $role_id = null;
    
    
    protected function rules()
    {
        return [
            'type_development' => 'required',
            'task_name' => 'required|max:50',
            'sort_order' => 'required|numeric',
            'role_id' => 'required|exists:roles,id',
        ];
    }

    public function store()
    {
        $this->validate();
        
        DevelopmentTask::create([
            'type_development' => $this->type_development,
            'task_name' => $this->task_name,
            'sort_order' => $this->sort_order,
            'role_id' => $this->role_id,
        ]);

        $this->reset();
    }

    public function setNextOrder()
    {
        $lastOrder = DevelopmentTask::max('sort_order');
        $this->sort_order = $lastOrder ? $lastOrder + 1 : 1;
    }

    public function setDevelopTask(DevelopmentTask $developmentTask)
    {
        $this->id = $developmentTask->id;
        $this->type_development = $developmentTask->type_development;
        $this->task_name = $developmentTask->task_name;
        $this->sort_order = $developmentTask->sort_order;
        $this->role_id = $developmentTask->role_id;
    }

    public function update()
    {
        $this->validate();
        $task = DevelopmentTask::findOrFail($this->id);
        
        $task->update([
            'type_development' => $this->type_development,
            'task_name' => $this->task_name,
            'sort_order' => $this->sort_order,
            'role_id' => $this->role_id,
        ]);
        
        $this->reset();
    }
}
