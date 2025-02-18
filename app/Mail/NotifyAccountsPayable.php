<?php

namespace App\Mail;

use App\Models\Requisition;
use App\Models\RequisitionVendor;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyAccountsPayable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public RequisitionVendor $vendor)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Incoming Invoices | Procurement Requisition Application',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $requisition = $this->vendor->requisition;
        $date_sent = Carbon::parse($this->vendor->ap->date_received)->format('F jS, Y');
        return new Content(
            markdown: 'emails.sent-to-accounts-payable',
            with: [
                'vendor' => $this->vendor,
                'requisition' => $requisition,
                'date_sent' => $date_sent,
                'url' => route('accounts_payable.view', $this->vendor->ap->id),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
