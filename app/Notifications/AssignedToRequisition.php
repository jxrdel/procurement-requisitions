<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignedToRequisition extends Notification implements ShouldQueue
{
    use Queueable;

    public $requisition;

    public bool $mailOnly = false;

    /**
     * Create a new notification instance.
     */
    public function __construct($requisition)
    {
        $this->requisition = $requisition;
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
            ->subject('Requisition Assigned to You | PRA')
            ->markdown('emails.requisition-assigned', ['requisition' => $this->requisition]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Requisition Assigned',
            'message' => 'You have been assigned a new requisition: ' . $this->requisition->requisition_no,
            'url' => route('requisitions.view', $this->requisition),
        ];
    }
}
