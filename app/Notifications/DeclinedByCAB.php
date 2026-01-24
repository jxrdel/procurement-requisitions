<?php

namespace App\Notifications;

use App\Models\RequisitionRequestForm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeclinedByCAB extends Notification implements ShouldQueue
{
    use Queueable;

    public bool $mailOnly = false;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected RequisitionRequestForm $form)
    {
        $this->form->loadMissing(['headOfDepartment', 'contactPerson']);
    }

    /**
     * Set the notification to be sent as mail-only.
     *
     * @return $this
     */
    public function mailOnly(): self
    {
        $this->mailOnly = true;

        return $this;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $this->mailOnly ? ['mail'] : ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $ccRecipients = [];

        if ($this->form->headOfDepartment) {
            $ccRecipients[] = $this->form->headOfDepartment->email;
        }

        return (new MailMessage)
            ->subject('Availability of Funding Denied by Cost & Budgeting | PRA')
            ->cc($ccRecipients)
            ->markdown('emails.declined-by-cab', [
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
            "title" => "Availability of Funding Denied by Cost & Budgeting",
            "message" => "A Requisition Form #{$this->form->form_code} has been denied availability of funding by Cost & Budgeting. Reason: {$this->form->cab_reason_for_denial}",
            "url" => route("requisition_forms.view", ["id" => $this->form->id])
        ];
    }
}
