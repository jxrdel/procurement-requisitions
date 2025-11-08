<?php

namespace App\Livewire;

use App\Mail\ErrorNotification;
use App\Mail\NotifyVoteControl;
use App\Models\APVendor;
use App\Models\RequisitionVendor;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ViewAccountsPayableVendor extends Component
{
    public $ap_vendor;
    public $vendor;
    public $invoices;
    public $requisition;
    public $requisition_vendors = [];

    public $date_received_ap;
    public $date_sent_vc;

    public $date_received_ap_invoices;
    public $date_sent_vc_invoices;

    public $isEditing = true;
    public $accordionView = 'show';

    public function mount($id)
    {
        $this->ap_vendor = APVendor::find($id);
        $this->vendor = $this->ap_vendor->vendor;
        $this->invoices = $this->vendor->invoices;

        if (!$this->vendor) {
            return abort(404);
        }

        $this->requisition = $this->vendor->requisition;

        if ($this->requisition->is_first_pass) {
            $this->requisition_vendors = $this->requisition->vendors()->get()->toArray();
            $allDatesFilled = true;
            foreach ($this->requisition_vendors as $vendor) {
                if (empty($vendor['date_received_ap']) || empty($vendor['date_sent_vc'])) {
                    $allDatesFilled = false;
                    break;
                }
            }
            $this->isEditing = !$allDatesFilled;
        } else {
            $this->date_received_ap = $this->vendor->date_received_ap;
            $this->date_sent_vc = $this->vendor->date_sent_vc;
            $this->date_received_ap_invoices = $this->vendor->date_received_ap_invoices;
            $this->date_sent_vc_invoices = $this->vendor->date_sent_vc_invoices;

            if ($this->date_received_ap_invoices !== null && $this->date_sent_vc_invoices !== null) {
                $this->isEditing = false;
            }
        }

        if (Auth::user()->role->name === 'Viewer') {
            $this->isEditing = false;
        }
    }

    public function render()
    {
        return view('livewire.view-accounts-payable-vendor')->title($this->vendor->vendor_name . ' | View Vendor');
    }


    public function edit()
    {
        try {
            if ($this->requisition->is_first_pass) {
                foreach ($this->requisition_vendors as $index => $vendorData) {
                    if ($vendorData['date_received_ap'] == '') {
                        $this->requisition_vendors[$index]['date_received_ap'] = null;
                    }

                    if ($vendorData['date_sent_vc'] == '') {
                        $this->requisition_vendors[$index]['date_sent_vc'] = null;
                    }

                    $this->validate(
                        [
                            'requisition_vendors.' . $index . '.date_received_ap' => 'nullable|date|after_or_equal:' . $this->requisition->date_sent_dps,
                            'requisition_vendors.' . $index . '.date_sent_vc' => 'nullable|date|after_or_equal:requisition_vendors.' . $index . '.date_received_ap',
                        ],
                        [
                            'requisition_vendors.' . $index . '.date_received_ap.after_or_equal' => 'Please check date',
                            'requisition_vendors.' . $index . '.date_sent_vc.after_or_equal' => 'Please check date',
                        ]
                    );

                    $vendor = RequisitionVendor::find($vendorData['id']);
                    $vendor->update([
                        'date_received_ap' => $vendorData['date_received_ap'],
                        'date_sent_vc' => $vendorData['date_sent_vc'],
                    ]);
                }
            } else {
                if ($this->date_received_ap == '') {
                    $this->date_received_ap = null;
                }

                if ($this->date_sent_vc == '') {
                    $this->date_sent_vc = null;
                }

                if ($this->date_received_ap_invoices == '') {
                    $this->date_received_ap_invoices = null;
                }

                if ($this->date_sent_vc_invoices == '') {
                    $this->date_sent_vc_invoices = null;
                }

                $this->validate(
                    [
                        'date_received_ap' => 'nullable|date|after_or_equal:' . $this->requisition->date_sent_dps,
                        'date_sent_vc' => 'nullable|date|after_or_equal:' . $this->date_received_ap,
                        'date_received_ap_invoices' => 'nullable|date|after_or_equal:' . $this->requisition->date_sent_dps,
                        'date_sent_vc_invoices' => 'nullable|date|after_or_equal:' . $this->date_received_ap_invoices,
                    ],
                    [
                        'date_received_ap.after_or_equal' => 'Please check date',
                        'date_sent_vc.after_or_equal' => 'Please check date',
                        'date_received_ap_invoices.after_or_equal' => 'Please check date',
                        'date_sent_vc_invoices.after_or_equal' => 'Please check date',
                    ]
                );

                $status = $this->getStatus();

                $this->vendor->update([
                    'requisition_status' => $status,
                    'date_received_ap' => $this->date_received_ap,
                    'date_sent_vc' => $this->date_sent_vc,
                    'date_received_ap_invoices' => $this->date_received_ap_invoices,
                    'date_sent_vc_invoices' => $this->date_sent_vc_invoices,
                ]);
            }

            Log::info('Vendor ' . $this->vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was edited by ' . Auth::user()->name . ' from Accounts Payable');
            $this->isEditing = false;
            $this->dispatch('show-message', message: 'Record edited successfully');
            $this->requisition = $this->requisition->fresh();
            $this->ap_vendor = $this->ap_vendor->fresh();
        } catch (Exception $e) {
            Log::error('Error from user ' . Auth::user()->username . ' while editing a requisition in Accounts Payable: ' . $e->getMessage());
            Mail::to('jardel.regis@health.gov.tt')->queue(new ErrorNotification(Auth::user()->username, $e->getMessage()));
            dd('Error editing requisition. Please contact the Ministry of Health Helpdesk at 217-4664 ext. 11000 or ext 11124', $e->getMessage());
        }
    }

    public function getFormattedDate($date)
    {
        if ($date !== null) {
            return Carbon::parse($date)->format('F jS, Y');
        }
    }

    public function getIsButtonDisabledProperty()
    {
        if ($this->requisition->is_first_pass) {
            foreach ($this->requisition_vendors as $vendor) {
                if (empty($vendor['date_received_ap']) || empty($vendor['date_sent_vc'])) {
                    return true;
                }
            }
            return false;
        } else {
            return $this->date_received_ap_invoices === null || $this->date_sent_vc_invoices === null;
        }
    }

    public function getStatus()
    {
        if ($this->requisition->is_completed) {
            return 'Completed';
        }

        $status = 'At Accounts Payable';

        if ($this->date_received_ap_invoices && $this->date_sent_vc_invoices && !$this->ap_vendor->is_completed) {
            $status = 'To Be Sent to Vote Control';
        }

        return $status;
    }
    public function toggleAccordionView()
    {
        $this->accordionView = $this->accordionView === 'show' ? 'hide' : 'show';

        $this->skipRender();
    }

    public function sendToVoteControl()
    {
        $this->vendor->update([
            'vendor_status' => 'Sent to Vote Control',
        ]);

        $this->ap_vendor->update([
            'is_completed' => true,
            'date_completed' => now(),
        ]);

        if ($this->vendor->voteControl) {
            $this->vendor->voteControl->update([
                'date_received' => Carbon::now(),
                'is_completed' => false,
            ]);
        } else {
            $this->vendor->voteControl()->create([
                'date_received' => Carbon::now(),
            ]);
        }

        //Send email to Vote Control
        if ($this->requisition->is_first_pass) {
            Log::info('Requisition #' . $this->requisition->requisition_no . ' was sent to Vote Control for Commitment by ' . Auth::user()->name . ' from Accounts Payable');
        } else {
            Log::info('Vendor ' . $this->vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was sent to Vote Control by ' . Auth::user()->name . ' from Accounts Payable');
        }

        //Get Vote Control users
        $users = User::voteControl()->get();

        foreach ($users as $user) {
            // Mail::to($user->email)->send(new NotifyVoteControl($this->vendor));
        }

        return redirect()->route('accounts_payable.index')->with('success', 'Sent to Vote Control successfully');
    }
}
