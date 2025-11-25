<?php

namespace App\Notifications;

use App\Models\RequisitionRequestForm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeclinedByProcurement extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected RequisitionRequestForm $form)
    {
        $this->form->loadMissing(['headOfDepartment', 'contactPerson']);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // 1. Collect the emails of people to be CC'd
        $ccRecipients = [];

        // Assuming 'headOfDepartment' is the relation for head_of_department_id
        if ($this->form->headOfDepartment) {
            $ccRecipients[] = $this->form->headOfDepartment->email;
        }

        // Assuming 'contactPerson' is the relation for contact_person_id
        if ($this->form->contactPerson) {
            $ccRecipients[] = $this->form->contactPerson->email;
        }
        return (new MailMessage)
            ->subject('Requisition Form Declined by Procurement | PRA')
            ->cc($ccRecipients)
            ->markdown('emails.declined-by-procurement', [
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
            "title" => "Requisition Form Denied by Procurement",
            "message" => "A Requisition Form #{$this->form->form_code} has been denied by Procurement. Reason: {$this->form->procurement_reason_for_denial}",
            "url" => route("requisition_forms.view", ["id" => $this->form->id])
        ];
    }
}
