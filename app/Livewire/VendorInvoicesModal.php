<?php

namespace App\Livewire;

use App\Models\RequisitionVendor;
use Livewire\Attributes\On;
use Livewire\Component;

class VendorInvoicesModal extends Component
{
    public $vendor;
    public $vendor_name;
    public $invoices = [];
    public $invoice_no;
    public $invoice_amount;
    public $date_invoice_received;
    public $date_sent_commit;
    public $date_sent_ap;
    public $requisition;

    public function render()
    {
        return view('livewire.vendor-invoices-modal');
    }

    // public function mount()
    // {
    //     $this->invoices = [];
    // }

    #[On('show-invoices-modal')]
    public function displayModal($id)
    {
        // dd($id);
        $this->vendor = RequisitionVendor::find($id);
        $this->requisition = $this->vendor->requisition;
        $this->invoices = $this->vendor->invoices;
        $this->vendor_name = $this->vendor->vendor_name;
        $this->dispatch('display-invoices-modal');
    }

    public function saveInvoice()
    {
        // dd($this->invoice_amount);
        $this->validate([
            'invoice_no' => 'required',
            'invoice_amount' => 'required',
            'date_invoice_received' => 'required',
        ]);

        $this->vendor->invoices()->create([
            'invoice_no' => $this->invoice_no,
            'invoice_amount' => $this->invoice_amount,
            'date_invoice_received' => $this->date_invoice_received,
            'date_sent_commit' => $this->date_sent_commit,
            'date_sent_ap' => $this->date_sent_ap,
        ]);

        $this->invoice_no = null;
        $this->invoice_amount = null;
        $this->date_invoice_received = null;

        $this->invoices = $this->vendor->invoices;
        $this->dispatch('show-message', message: 'Invoice added successfully');
        // $this->dispatch('refresh-vendors')->to(ViewRequisition::class);
    }

    public function deleteInvoice($id)
    {
        $invoice = $this->vendor->invoices()->find($id);
        $invoice->delete();
        $this->invoices = $this->vendor->invoices;
        $this->dispatch('show-message', message: 'Invoice deleted successfully');
        // $this->dispatch('refresh-component')->to(ViewRequisition::class);
    }
}
