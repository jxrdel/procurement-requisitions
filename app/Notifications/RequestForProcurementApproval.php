<?php

namespace App\Notifications;

use App\Models\RequisitionRequestForm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestForProcurementApproval extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected RequisitionRequestForm $form)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Requisition Form Sent for Approval | PRA')
            ->markdown('emails.request-for-procurement-approval', [
                "recipient" => $notifiable->name,
                "form" => $this->form,
                "url" => route("requisition_forms.view", ["id" => $this->form->id])
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            "title" => "Requisition Form Sent for Approval",
            "message" => "A Requisition Form #{$this->form->form_code} has been updated as requested and has been resubmitted for your review and approval",
            "url" => route("requisition_forms.view", ["id" => $this->form->id])
        ];
    }
}
