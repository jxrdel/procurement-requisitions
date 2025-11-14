<?php

namespace App\Livewire;

use App\Mail\ErrorNotification;
use App\Mail\NotifyCheckRoom;
use App\Models\RequisitionVendor;
use App\Models\User;
use App\Models\VoteControlVendor;
use App\Notifications\FundsCommitted;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ViewVoteControlVendor extends Component
{
    public $vendor;
    public $vc_vendor;
    public $requisition;
    public $voucher_no;
    public $batch_no;
    public $date_sent_checkstaff;
    public $invoices;
    public $requisition_vendors = [];

    public $isEditing = true;
    public $accordionView = 'show';
    public function mount($id)
    {
        $this->vc_vendor = VoteControlVendor::find($id);
        $this->vendor = $this->vc_vendor->vendor;
        $this->invoices = $this->vendor->invoices;

        if (!$this->vc_vendor) {
            return abort(404);
        }

        $this->requisition = $this->vendor->requisition;

        if ($this->requisition->is_first_pass) {
            $this->requisition_vendors = $this->requisition->vendors()->get()->toArray();
            $allDatesFilled = true;
            foreach ($this->requisition_vendors as $vendor) {
                if (empty($vendor['date_committed_vc'])) {
                    $allDatesFilled = false;
                    break;
                }
            }
            $this->isEditing = !$allDatesFilled;
        } else {
            $this->batch_no = $this->vendor->batch_no;
            $this->voucher_no = $this->vendor->voucher_no;
            $this->date_sent_checkstaff = $this->vendor->date_sent_checkstaff;

            if (($this->batch_no !== null || trim($this->batch_no) == '') && ($this->voucher_no !== null && trim($this->voucher_no) !== '')) {
                $this->isEditing = false;
            }
        }

        if(!$this->requisition->is_first_pass && $this->vc_vendor->is_completed == "1") {
            $this->isEditing = false;
        }

        if (Auth::user()->role->name === 'Viewer') {
            $this->isEditing = false;
        }
    }

    public function render()
    {
        return view('livewire.view-vote-control-vendor')->title($this->vendor->vendor_name . ' | View Vendor');
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
                if (empty($vendor['date_committed_vc'])) {
                    return true;
                }
            }
            return false;
        } else {
            return
                $this->voucher_no === null || trim($this->voucher_no) === '' ||
                $this->batch_no === null || trim($this->batch_no) === '' ||
                $this->date_sent_checkstaff === null;
        }
    }
    public function edit()
    {
        try {
            if ($this->requisition->is_first_pass) {
                foreach ($this->requisition_vendors as $index => $vendorData) {
                    if ($vendorData['date_committed_vc'] == '') {
                        $this->requisition_vendors[$index]['date_committed_vc'] = null;
                    }

                    $this->validate(
                        [
                            'requisition_vendors.' . $index . '.date_committed_vc' => 'required|date|date_format:Y-m-d',
                        ],
                        [
                            'requisition_vendors.' . $index . '.date_committed_vc.required' => 'Please enter date',
                        ]
                    );

                    $vendor = RequisitionVendor::find($vendorData['id']);
                    $vendor->update([
                        'date_committed_vc' => $this->requisition_vendors[$index]['date_committed_vc'],
                    ]);
                }
            } else {
                if ($this->date_sent_checkstaff == '') {
                    $this->date_sent_checkstaff = null;
                }

                $status = $this->vendor->vendor_status;

                if (!$this->vc_vendor->is_completed) {
                    $status = $this->getStatus();
                }

                $this->vendor->update([
                    'batch_no' => $this->batch_no,
                    'voucher_no' => $this->voucher_no,
                    'date_sent_checkstaff' => $this->date_sent_checkstaff,
                    'vendor_status' => $status,
                ]);
            }

            Log::info('Vendor ' . $this->vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was edited by ' . Auth::user()->name . ' from Vote Control');
            $this->isEditing = false;
            $this->resetValidation();
            $this->dispatch('show-message', message: 'Record edited successfully');
            $this->vendor = $this->vendor->fresh();
            $this->vc_vendor = $this->vc_vendor->fresh();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error('Error from user ' . Auth::user()->username . ' while editing a requisition in Accounts Payable: ' . $e->getMessage());
            Mail::to('jardel.regis@health.gov.tt')->queue(new ErrorNotification(Auth::user()->username, $e->getMessage()));
            dd('Error editing requisition. Please contact the Ministry of Health Helpdesk at 217-4664 ext. 11000 or ext 11124', $e->getMessage());
        }
    }
    public function getStatus()
    {
        $status = 'At Vote Control';

        if ($this->batch_no && $this->voucher_no && !$this->vc_vendor->is_completed) {
            $status = 'To Be Sent to Check Staff';
        }

        return $status;
    }

    public function sendToProcurement()
    {
        $this->vc_vendor->update([
            'is_completed' => true,
            'date_completed' => now(),
        ]);

        //update all requisition vendors
        foreach ($this->requisition_vendors as $vendorData) {
            $vendor = RequisitionVendor::find($vendorData['id']);
            $vendor->update([
                'vendor_status' => 'Sent to Procurement',
                'sent_to_ap' => false,
            ]);
        }

        // Procurement officer for requisition
        $procurement_user = $this->requisition->procurement_officer;
        Notification::send($procurement_user, new FundsCommitted($this->requisition));

        $this->requisition->update([
            'requisition_status' => 'Sent to Procurement',
            'is_first_pass' => false,
        ]);

        $this->requisition->statusLogs()->create([
            'details' => 'Funds committed by Vote Control and sent to Procurement by ' . Auth::user()->name,
            'created_by' => Auth::user()->username,
        ]);
        return redirect()->route('vote_control.index')->with('success', 'Sent to Procurement successfully');
    }

    public function sendToCheckStaff()
    {
        $this->vc_vendor->update([
            'is_completed' => true,
            'date_completed' => now(),
        ]);

        $this->vendor->update([
            'vendor_status' => 'Sent to Check Staff',
        ]);

        $this->vendor->checkStaff()->create([
            'date_received' => Carbon::now(),
        ]);

        Log::info('Vendor ' . $this->vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was sent to Check Staff by ' . Auth::user()->name . ' from Vote Control');

        //Get Emails of Check Staff
        $checkStaff = User::checkStaff()->get();
        foreach ($checkStaff as $staff) {
            // Mail::to($staff->email)->queue(new NotifyCheckRoom($this->vendor));
        }

        return redirect()->route('vote_control.index')->with('success', 'Sent to Check Staff successfully');
    }

    public function toggleAccordionView()
    {
        $this->accordionView = $this->accordionView === 'show' ? 'hide' : 'show';

        $this->skipRender();
    }
}
