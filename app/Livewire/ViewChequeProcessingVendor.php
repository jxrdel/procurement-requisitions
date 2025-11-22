<?php

namespace App\Livewire;

use App\Models\Cheque;
use App\Models\ChequeProcessingVendor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ViewChequeProcessingVendor extends Component
{
    public $vendor;
    public $requisition;
    public $cp_vendor;
    public $cheques;
    public $deleted_cheques = [];

    public $date_cheque_processed;
    public $cheque_no;
    public $date_of_cheque;
    public $date_sent_dispatch;
    public $invoices;

    public $accordionView = 'show';
    public $isEditing = true;

    public function render()
    {
        return view('livewire.view-cheque-processing-vendor')->title($this->vendor->vendor_name . ' | View Vendor');
    }

    public function mount($id)
    {
        $this->cp_vendor = ChequeProcessingVendor::find($id);
        $this->vendor = $this->cp_vendor->vendor;
        $this->invoices = $this->vendor->invoices;

        if (!$this->cp_vendor) {
            return abort(404);
        }

        $this->cheques = $this->vendor->cheques()->get()->toArray();

        if (count($this->cheques) > 0) {
            $this->isEditing = false;
        }

        if (count($this->cheques) === 0) { //Adds a cheque if there are none
            $this->addCheque();
        }

        $this->requisition = $this->vendor->requisition;

        $this->date_cheque_processed = $this->vendor->date_cheque_processed;
        $this->cheque_no = $this->vendor->cheque_no;
        $this->date_of_cheque = $this->vendor->date_of_cheque;
        $this->date_sent_dispatch = $this->vendor->date_sent_dispatch;

        if (Auth::user()->role->name === 'Viewer') {
            $this->isEditing = false;
        }
    }

    public function getIsButtonDisabledProperty()
    {
        if (count($this->cheques) === 0) {
            return true; // Disable if no cheques exist
        }

        $totalChequeAmount = collect($this->cheques)->sum('cheque_amount');

        foreach ($this->cheques as $cheque) {
            if (
                empty($cheque['date_cheque_processed']) ||
                empty($cheque['cheque_no']) ||
                empty($cheque['date_of_cheque']) ||
                empty($cheque['date_sent_dispatch'])
            ) {
                return true; // Disable the button if any cheque is incomplete
            }
        }

        // Disable the button if the total cheque amount does not match the vendor amount
        if ($totalChequeAmount !== (float) $this->invoices->sum('invoice_amount')) {
            return true;
        }

        return false; // Enable the button if all conditions are met
    }



    public function edit()
    {
        $this->resetErrorBag();
        if ($this->date_cheque_processed === '') {
            $this->date_cheque_processed = null;
        }

        if ($this->date_of_cheque === '') {
            $this->date_of_cheque = null;
        }

        if ($this->date_sent_dispatch === '') {
            $this->date_sent_dispatch = null;
        }

        if (trim($this->cheque_no) === '') {
            $this->cheque_no = null;
        }

        $status = $this->vendor->vendor_status;

        if (!$this->cp_vendor->is_completed) {
            $status = $this->getStatus();
        }

        // Get the list of existing cheque numbers and count occurrences
        $chequeNumberCounts = array_count_values(collect(Cheque::all())->pluck('cheque_no')->toArray());
        // dd($chequeNumberCounts);

        // Get the total existing cheque amount
        $totalChequeAmount = collect($this->cheques)->sum('cheque_amount');
        if ($totalChequeAmount > $this->invoices->sum('invoice_amount')) {
            $this->addError('cheques.0.cheque_amount', 'The total cheque amount must not exceed the vendor amount.');
            return;
        }

        if ($this->hasDuplicateChequeNumbers()) {
            $this->addError('cheques.0.cheque_no', 'Cheque number must be unique.');
            return;
        }

        $this->validate(
            [
                'cheques.*.date_cheque_processed' => 'nullable|date|date_format:Y-m-d|after_or_equal:' . $this->requisition->ps_approval_date,
                'cheques.*.cheque_no' => [
                    'regex:/^[A-Za-z]{1}[0-9]{8}$/', // Check if cheque number is in the format A12345678
                    'nullable',
                    function ($attribute, $value, $fail) use ($chequeNumberCounts) {
                        if (!empty($value) && isset($chequeNumberCounts[$value]) && $chequeNumberCounts[$value] > 1) {
                            $fail('The cheque number must be unique.');
                        }
                    }
                ],
                'cheques.*.cheque_amount' => 'required|numeric',
                'cheques.*.date_of_cheque' => 'nullable|date|date_format:Y-m-d|after_or_equal:' . $this->requisition->ps_approval_date,
                'cheques.*.date_sent_dispatch' => 'nullable|date|date_format:Y-m-d|after_or_equal:date_of_cheque',
            ],
            [
                'cheques.*.cheque_no.regex' => 'Cheque number must be in the format A12345678',
                'cheques.*.date_cheque_processed.after_or_equal' => 'Please check date',
                'cheques.*.date_of_cheque.after_or_equal' => 'Please check date',
                'cheques.*.date_sent_dispatch.after_or_equal' => 'Please check date',
            ]
        );


        if ($this->deleted_cheques) {
            $this->vendor->cheques()->whereIn('id', $this->deleted_cheques)->delete();
        }

        foreach ($this->cheques as $cheque) {
            $this->vendor->cheques()->updateOrCreate(
                ['id' => $cheque['id']],
                [
                    'date_cheque_processed' => $cheque['date_cheque_processed'],
                    'cheque_no' => $cheque['cheque_no'],
                    'cheque_amount' => $cheque['cheque_amount'],
                    'date_of_cheque' => $cheque['date_of_cheque'],
                    'date_sent_dispatch' => $cheque['date_sent_dispatch'],
                    'invoice_no' => $cheque['invoice_no'],
                ]
            );
        }

        // $this->vendor->update([
        //     'vendor_status' => $status,
        //     'date_cheque_processed' => $this->date_cheque_processed,
        //     'cheque_no' => $this->cheque_no,
        //     'date_of_cheque' => $this->date_of_cheque,
        //     'date_sent_dispatch' => $this->date_sent_dispatch,
        // ]);

        Log::info('Vendor ' . $this->vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was edited by ' . Auth::user()->name . ' from Cheque Processing');
        $this->isEditing = false;
        $this->resetValidation();
        $this->dispatch('show-message', message: 'Record edited successfully');
        $this->vendor = $this->vendor->fresh();
        $this->cp_vendor = $this->cp_vendor->fresh();
        $this->cheques = $this->vendor->cheques()->get()->toArray();
    }

    public function getStatus()
    {

        $status = 'At Cheque Processing';

        if ($this->date_cheque_processed && !$this->cp_vendor->is_completed) {
            $status = 'Cheque Details to be Entered';
        }

        if ($this->date_cheque_processed && $this->cheque_no && $this->date_of_cheque && !$this->date_sent_dispatch) {
            $status = 'To be Sent to Cheque Dispatch';
        }

        if ($this->date_cheque_processed && $this->cheque_no && $this->date_of_cheque && $this->date_sent_dispatch) {
            $status = 'To Be Completed by Cheque Processing';
        }

        return $status;
    }

    public function getFormattedDate($date)
    {
        if ($date !== null) {
            return Carbon::parse($date)->format('F jS, Y');
        }
    }

    public function completeVendor()
    {
        $this->cp_vendor->update([
            'is_completed' => true,
            'date_completed' => now(),
        ]);

        $this->vendor->update([
            'vendor_status' => 'Completed',
            'is_completed' => true,
            'date_completed' => now(),
        ]);


        Log::info('Vendor ' . $this->vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was completed by ' . Auth::user()->name . ' from Cheque Processing');
        //Check if all vendor status are completed
        if ($this->requisition->isCompleted()) {
            Log::info('Requisition #' . $this->requisition->requisition_no . ' was completed by ' . Auth::user()->name . ' from Cheque Processing');
            $this->requisition->update([
                'requisition_status' => 'Completed',
                'is_completed' => true,
                'date_completed' => now(),
            ]);

            $this->requisition->statuslogs()->create([
                'details' => 'Requisition marked as Completed by ' . Auth::user()->name . ' from Cheque Processing',
                'created_by' => Auth::user()->name,
            ]);

            $assigned_to = $this->requisition->procurement_officer;
            $maryann = User::where('name', 'Maryann Basdeo')->first();
            $contactPerson = $this->requisition->requisitionForm?->contactPerson;
            $hod = $this->requisition->requisitionForm?->headOfDepartment;

            $recipients = collect([$assigned_to, $maryann, $contactPerson, $hod])
                // Remove nulls (e.g., if maryann isn't found or contactPerson is null)
                ->filter()
                // Remove duplicates based on User ID (fixes the Maryann/Officer overlap)
                ->unique('id');

            Notification::send($recipients, new \App\Notifications\RequisitionCompleted($this->requisition));
        }
        return redirect()->route('cheque_processing.index')->with('success', 'Completed successfully');
    }

    public function addCheque()
    {
        $this->cheques[] = [
            'id' => null,
            'date_cheque_processed' => null,
            'cheque_amount' => "0.00",
            'cheque_no' => '',
            'invoice_no' => '',
            'date_of_cheque' => null,
            'date_sent_dispatch' => null,
        ];
    }

    public function removeCheque($index)
    {
        $cheque = $this->cheques[$index];

        if ($cheque['id']) {
            $this->deleted_cheques[] = $cheque['id'];
        }

        unset($this->cheques[$index]);
    }

    public function toggleAccordionView()
    {
        $this->accordionView = $this->accordionView === 'show' ? 'hide' : 'show';

        $this->skipRender();
    }

    public function hasDuplicateChequeNumbers(): bool
    {
        $chequeNumbers = array_column($this->cheques, 'cheque_no');

        // Count occurrences of each cheque number
        $duplicateCheques = array_filter(array_count_values($chequeNumbers), function ($count) {
            return $count > 1;
        });

        return !empty($duplicateCheques);
    }
}
