<?php

namespace App\Notifications;

use App\Models\RequisitionRequestForm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestForHODApproval extends Notification implements ShouldQueue
{
    use Queueable;

    public bool $mailOnly = false;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected RequisitionRequestForm $form)
    {
        //
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
        return (new MailMessage)
            ->subject('Requisition Form Sent for Approval | PRA')
            ->markdown('emails.request-for-hod-approval', [
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
            "message" => "A new Requisition Form #{$this->form->form_code} has been sent to you for approval. Minute: {$this->form->contact_person_note}",
            "url" => route("requisition_forms.view", ["id" => $this->form->id])
        ];
    }
}
