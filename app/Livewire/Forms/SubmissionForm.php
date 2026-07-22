<?php

namespace App\Livewire\Forms;


use Carbon\Carbon;
use Livewire\Form;
use App\Models\User;
use App\Models\Historie;
use App\Models\Workflow;
use App\Models\Submission;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class SubmissionForm extends Form
{
    public ?int $id = null;
    public ?string $no_ticket = null;
    public ?string $submission_date = null;
    public ?string $user_name = null;
    public ?int $user_id = null;
    public ?int $bp_id = null;
    public ?int $departement_id = null;
    public ?string $type_development = '';
    public ?string $application_name = null;
    public ?string $background = '';
    public ?string $cost_benefit = '';
    public ?string $risk = '';
    public string $status = ''; 
    public string $last_remark = '-';
    public ?string $application_scope = '';
    public $themes = [];
    public ?string $submission_id = null;
    public $disposition_to = '';
    public mixed $document_brd = null;
    public ?Submission $submission = null;

    protected function rules()
    {
        return [
            'no_ticket' => 'nullable|unique:submissions,no_ticket,' . $this->id,
            'submission_date' => 'required|date',
            'type_development' => 'required',
            'user_name' => 'required',
            'background' => 'required|min:50|max:1000',
            'departement_id' => 'required|exists:departements,id',
            'application_name' => 'required|max:50|min:5',
            'application_scope' => 'required|min:10|max:1000',
            'cost_benefit' => 'required|min:10|max:1000',
            'risk' => 'required|min:10|max:1000',
            'themes' => 'required'
        ];
    }

    protected function validationAttributes() 
    {
        return [
            'no_ticket' => 'No Tiket',
            'submission_date' => 'Tanggal Pengajuan',
            'type_development' => 'Jenis pengembangan',
            'user_name' => 'Nama',
            'background' => 'Latar Belakang',
            'departement_id' => 'Unit kerja',
            'application_name' => 'Nama Aplikasi',
            'application_scope' => 'Lingkup Aplikasi',
            'cost_benefit' => 'Cost dan Benefit',
            'risk' => 'Resiko',
            'themes' => 'Tema'
        ];
    }

    protected function messages()
    {
        return [
            'no_ticket.unique' => ':attribute sudah tersedia',
            'submission_date.required' => ':attribute wajib diisi',
            'type_development.required' => ':attribute wajin diisi',
            'user_name.required' => ':attribute wajib diisi',
            'background.required' => ':attribute wajib diisi',
            'background.min' => ':attribute minimal :min karakter',
            'background.max' => ':attribute maximal :max karakter',
            'departement_id.required' => ':attribute wajib diisi',
            'application_name.required' => ':attribute wajib diisi',
            'application_name.min' => ':attribute minimal :min karakter',
            'application_name.max' => ':attribute maximal :max karakter',
            'application_scope.required' => ':attribute wajib diisi',
            'application_scope.min' => ':attribute minimal :min karakter',
            'application_scope.max' => ':attribute maximal :max karakter',
            'cost_benefit.required' => ':attribute wajib diisi',
            'cost_benefit.min' => ':attribute minimal :min karakter',
            'cost_benefit.max' => ':attribute maximal :max karakter',
            'risk.required' => ':attribute wajib diisi',
            'risk.min' => ':attribute minimal :min karakter',
            'risk.max' => ':attribute maximal :max karakter',
            'themes.required' => ':attribute wajib diisi'
        ];
    }

    public function store()
    {
        $this->validate();

        $firstStep = Workflow::query()
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->first();
            
        $initialStatus = $firstStep ? $firstStep->state_code : 'pending';
        $initialWorkflowId = $firstStep ? $firstStep->id : null;
        
        $date = Carbon::now()->format('Ymd');
        $random = strtoupper(Str::random(5));
        $generatedTicket = "TXT-{$date}-{$random}";

        $submission = Submission::create([
            'no_ticket' => $generatedTicket,
            'submission_date' => $this->submission_date,
            'user_id' => Auth::id(), 
            'type_development' => $this->type_development,
            'departement_id' => $this->departement_id,
            'application_name' => $this->application_name,
            'background' => $this->background,
            'cost_benefit' => $this->cost_benefit,
            'risk' => $this->risk,
            'status' => $initialStatus, 
            'application_scope' => $this->application_scope,
            'workflow_id' => $initialWorkflowId,
        ]);

        if ($firstStep && $firstStep->role_id) {
            $targets = User::query()
                ->where('role_id', $firstStep->role_id)
                ->where('departement_id', $this->departement_id)
                ->get();
                
            foreach ($targets as $target) {
                $target->notify(new \App\Notifications\SubmissionNotification(
                    $submission, 
                    "Menunggu persetujuan anda"
                ));
            }
        }

        Historie::create([
            'submission_id' => $submission->id,
            'user_id'        => Auth::user()->id,
            'status'         => 'Submitted',
            'action'         => 'submitted',
            'information'    => 'Kirim Pengajuan',
        ]);

        $submission->themes()->sync($this->themes);
        $this->reset();
    }

    public function updatedRevision()
    {
        $this->validate();
        
        $submission = Submission::findOrFail($this->id);
        
        $submission->update([
            'submission_date' => $this->submission_date,
            'type_development' => $this->type_development,
            'departement_id' => $this->departement_id,
            'application_name' => $this->application_name,
            'background' => $this->background,
            'application_scope' => $this->application_scope,
            'cost_benefit' => $this->cost_benefit,
            'risk' => $this->risk,
        ]);
        
        $submission->themes()->sync($this->themes);

        Historie::create([
            'submission_id' => $submission->id,
            'user_id'       => Auth::id(),
            'action'        => 'UPDATE',
            'information'   => 'Mengirim ulang revisi pengajuan',
        ]);

        if ($submission->previous_state) {
            $submission->status = $submission->previous_state;
            $prevStep = Workflow::query()
                ->where('state_code', $submission->previous_state)
                ->first();
            $submission->workflow_id = $prevStep ? $prevStep->id : $submission->workflow_id;
        } else {
            $firstStep = Workflow::orderBy('sort_order', 'asc')->first();
            $submission->status = $firstStep->state_code;
            $submission->workflow_id = $firstStep->id;
        }

        $submission->previous_state = null;
        $submission->save();
        $currentWorkflow = Workflow::query()->find($submission->workflow_id);
        // $currentWorkflow = Workflow::find($submission->workflow_id);
        
        if ($currentWorkflow && $currentWorkflow->role_id) {
            $targets = User::query()
                ->where('role_id', $currentWorkflow->role_id)
                ->where('departement_id', $this->departement_id)
                ->get();
                
            foreach ($targets as $target) {
                $target->notify(new \App\Notifications\SubmissionNotification(
                    $submission, 
                    "Pengajuan Aplikasi {$submission->application_name} telah direvisi dan menunggu verifikasi Anda"
                ));
            }
        }
    }

    public function submitDisposition()
    {
        $this->validate([
            'disposition_to' => 'required|exists:users,id',
        ]);

        $submission = Submission::findOrFail($this->id);
        $currentWorkflow = Workflow::query()->find($submission->workflow_id);
        
        if (!$currentWorkflow) {
            return;
        }

        $nextWorkflow = Workflow::query()
            ->where('sort_order', '>', $currentWorkflow->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        $submission->update([
            'bp_id' => $this->disposition_to,
            'status' => $nextWorkflow?->state_code,
            'workflow_id' => $nextWorkflow ? $nextWorkflow->id : $submission->workflow_id,
        ]);

        $targetUser = User::query()->find($this->disposition_to);
        
        if ($targetUser) {
            $targetUser->notify(new \App\Notifications\SubmissionNotification(
                $submission, 
                "Anda telah ditunjuk sebagai Business Partner untuk pengajuan: {$submission->application_name}"
            ));
        }

        Historie::create([
            'submission_id' => $submission->id,
            'user_id' => Auth::id(),
            'action' => 'DISPOSITION',
            'information' => 'Mendisposisikan Business Partner',
        ]);
    }

    public function uploadBRD(Submission $submission)
    {
        $this->validate([
            'document_brd' => 'required|mimes:pdf,docx|max:2000'
        ]);

        $currentWorkflow = Workflow::query()->find($submission->workflow_id);

        $nextWorkflow = null;
        if ($currentWorkflow) {
            
        $nextWorkflow = Workflow::query()
            ->where('sort_order', '>', $currentWorkflow->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();
        }

        $targetStatus = $submission->previous_state ?: ($nextWorkflow?->state_code ?: $submission->status);
        $targetStep = Workflow::query()
            ->where('state_code', $targetStatus)
            ->first();
        
        if ($this->document_brd) {
            if ($submission->brd_path && Storage::disk('public')->exists($submission->brd_path)) {
                Storage::disk('public')->delete($submission->brd_path);
            }
        
        $path = $this->document_brd->store('documents/brd', 'public');
        
        $submission->update([
            'document_brd'   => $path,
            'status'         => $targetStatus,
            'previous_state' => null,
            'workflow_id'    => $targetStep ? $targetStep->id : $submission->workflow_id,
        ]);
        }

        if ($targetStep && $targetStep->role_id) {
            $targets = User::query()
                ->where('role_id', $targetStep->role_id)
                ->get();
                
            foreach ($targets as $target) {
                $target->notify(new \App\Notifications\SubmissionNotification(
                    $submission, 
                    "Menunggu verifikasi Anda"
                ));
            }
        }

        Historie::create([
            'submission_id' => $submission->id,
            'user_id'       => Auth::id(),
            'action'        => 'UPLOAD_BRD',
            'information'   => 'Membuat/Mengunggah BRD',
        ]);

        $this->reset('document_brd');
    }
    
}
