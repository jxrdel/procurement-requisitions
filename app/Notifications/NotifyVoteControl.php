<?php

namespace App\Notifications;

use App\Models\RequisitionVendor;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NotifyVoteControl extends Notification
{
    use Queueable;

    public $vendor;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(RequisitionVendor $vendor)
    {
        $this->vendor = $vendor;
        Log::info('Notification sent to Vote Control for Requisition ' . $this->vendor->requisition->requisition_no . ' from queue');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $requisition = $this->vendor->requisition;
        $date_sent = Carbon::parse($this->vendor->voteControl->date_received)->format('F jS, Y');

        $subject = $requisition->is_first_pass ?
            'Request for Commitment of Funds | Procurement Requisition Application' :
            'Incoming Invoices | Procurement Requisition Application';

        return (new MailMessage)
            ->subject($subject)
            ->markdown('emails.sent-to-vote-control', [
                'vendor' => $this->vendor,
                'requisition' => $requisition,
                'date_sent' => $date_sent,
                'url' => route('vote_control.view', $this->vendor->voteControl->id),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $title = $this->vendor->requisition->is_first_pass ?
            'Request for Commitment of Funds' :
            'Incoming Invoices';

        $message = $this->vendor->requisition->is_first_pass ?
            'A request for commitment of funds for Requisition ' . $this->vendor->requisition->requisition_no . ' has been sent to Vote Control.' :
            'Invoice(s) for Requisition ' . $this->vendor->requisition->requisition_no . ' have been sent to Vote Control.';

        return [
            'title' => $title,
            'message' => $message,
            'url' => route('vote_control.view', $this->vendor->voteControl->id),
        ];
    }
}
