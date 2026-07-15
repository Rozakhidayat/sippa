<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Workflow;
use App\Models\Submission;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;

class WorkflowForm extends Form
{
    public ?int $id = null;
    public ?string $label = null;
    public ?string $state_code = null;
    public ?string $sort_order = null;
    public ?int $role_id = null;
    public string $color = 'warning';

    protected function rules()
    {
        return [
            'label' => 'required',
            'state_code' => 'required|unique:workflows,state_code,' . $this->id,
            'sort_order' => 'required|numeric',
            'role_id' => 'required|exists:roles,id',
            'color' => 'required',
        ];
    }

    public function setWorkflow(Workflow $workflow)
    {
        $this->id = $workflow->id;
        $this->label = $workflow->label;
        $this->state_code = $workflow->state_code;
        $this->sort_order = $workflow->sort_order;
        $this->role_id = $workflow->role_id;
        $this->color = $workflow->color;
    }

    public function setNextOrder()
    {
        $lastOrder = Workflow::max('sort_order');
        $this->sort_order = $lastOrder ? $lastOrder + 1 : 1;
    }

    public function updatedLabel(string $value)
    {
        $this->state_code = Str::of($value)
            ->title()         
            ->replace(' ', '_')        
            ->trim('_');      
    }

    public function store()
    {
        $this->validate();
        
        Workflow::create([
            'label' => $this->label,
            'state_code' => str()->snake($this->state_code),
            'sort_order' => $this->sort_order,
            'role_id' => $this->role_id,
            'color' => $this->color,
        ]);

        $this->reset();
    }

    public function update()
    {
        $this->validate();
        
        $workflow = Workflow::findOrFail($this->id);
        $oldStateCode = $workflow->state_code;
        $newStateCode = str()->snake($this->state_code);

        $workflow->update([
            'label' => $this->label,
            'state_code' => $newStateCode,
            'sort_order' => $this->sort_order,
            'role_id' => $this->role_id,
            'color' => $this->color,
        ]);

        if ($oldStateCode !== $newStateCode) {
            Submission::query()
                ->where('status', $oldStateCode)
                    ->update(['status' => $newStateCode]);
        }

        $this->reset();
    }

}
