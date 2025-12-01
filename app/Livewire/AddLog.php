<?php

namespace App\Livewire;

use App\Mail\LogNotification;
use App\Models\Requisition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class AddLog extends Component
{
    public $logs;
    public $logdetails;
    public $requisition;

    public function render()
    {
        $this->logs = $this->requisition->statuslogs->filter(function ($log) {
            return Gate::allows('view-log', $log);
        });
        return view('livewire.add-log');
    }

    public function mount($id)
    {
        $this->requisition = Requisition::find($id);
        $this->logs = $this->requisition->statuslogs->filter(function ($log) {
            return Gate::allows('view-log', $log);
        });
    }


    public function addLog()
    {

        $newlog = $this->requisition->statuslogs()->create([
            'details' => $this->logdetails,
            'created_by' => Auth::user()->username,
        ]);

        $procuremenent_officer = $this->requisition->procurement_officer;

        // if ($procuremenent_officer) {
        //     Mail::to($procuremenent_officer->email)->cc('maryann.basdeo@health.gov.tt')->queue(new LogNotification($newlog));
        // } else {
        //     Mail::to('maryann.basdeo@health.gov.tt')->queue(new LogNotification($newlog));
        // }

        $this->logdetails = null;

        $this->dispatch('close-log-modal');
        $this->dispatch('show-message', message: 'Log added successfully');
    }


    public function deleteLog($id)
    {
        if (Gate::denies('delete-records')) {
            $this->dispatch('show-error', message: 'You are not authorized to delete this log');
            return;
        }

        $log = $this->requisition->statuslogs()->find($id);
        $log->delete();
        $this->dispatch('show-message', message: 'Log deleted successfully');
        $this->dispatch('preserveScroll');
    }
}
