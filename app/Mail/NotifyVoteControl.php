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
use Illuminate\Support\Facades\Log;

class NotifyVoteControl extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public RequisitionVendor $vendor)
    {
        Log::info('Notification sent to Vote Control for Requisition ' . $this->vendor->requisition->requisition_no . ' from queue');
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
            markdown: 'emails.sent-to-vote-control',
            with: [
                'vendor' => $this->vendor,
                'requisition' => $requisition,
                'date_sent' => $date_sent,
                'url' => route('vote_control.view', $this->vendor->voteControl->id),
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
