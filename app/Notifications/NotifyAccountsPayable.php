<?php

namespace App\Notifications;

use App\Models\RequisitionVendor;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NotifyAccountsPayable extends Notification
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
        Log::info('Notification sent to Accounts Payable for Requisition ' . $this->vendor->requisition->requisition_no . ' from queue');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
        $date_sent = Carbon::parse($this->vendor->ap->date_received)->format('F jS, Y');

        return (new MailMessage)
            ->subject('Incoming Requisition | Procurement Requisition Application')
            ->markdown('emails.sent-to-accounts-payable', [
                'vendor' => $this->vendor,
                'requisition' => $requisition,
                'date_sent' => $date_sent,
                'url' => route('accounts_payable.view', $this->vendor->ap->id),
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
        return [
            'title' => 'Incoming Requisition',
            'message' => 'Requisition #' . $this->vendor->requisition->requisition_no . ' has been sent to Accounts Payable.',
            'url' => route('accounts_payable.view', $this->vendor->ap->id),
        ];
    }
}
