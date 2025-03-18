<?php

namespace App\Mail;

use App\Models\Requisition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyCostBudgeting extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Requisition $requisition)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = '';
        if ($this->requisition->file_no === null) {
            $subject = 'Incoming Requisition | Procurement Requisition Application';
        } else {
            $subject = 'Incoming Requisition | ' . $this->requisition->file_no .  ' | Procurement Requisition Application';
        }
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.sent-to-cost-budgeting',
            with: [
                'requisiton' => $this->requisition,
                'url' => route('cost_and_budgeting.view', $this->requisition->cost_budgeting_requisition->id),
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
