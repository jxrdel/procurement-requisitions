<?php

namespace App\Notifications;

use App\Models\RequisitionRequestForm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovedByProcurement extends Notification
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
        // 1. Collect the emails of people to be CC'd
        $ccRecipients = [];

        if ($this->form->contactPerson) {
            $ccRecipients[] = $this->form->contactPerson->email;
        }

        return (new MailMessage)
            ->subject('Requisition Form Approved by Procurement | PRA')
            ->cc($ccRecipients)
            ->markdown('emails.approved-by-procurement', [
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
            "title" => "Requisition Form Approved by Procurement",
            "message" => "Requisition Form #{$this->form->form_code} has been approved by the Procurement Department.",
            "url" => route("requisition_forms.view", ["id" => $this->form->id])
        ];
    }
}
