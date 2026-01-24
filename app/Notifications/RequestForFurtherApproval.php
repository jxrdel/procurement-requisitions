<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\RequisitionRequestForm;
use App\Models\User;

class RequestForFurtherApproval extends Notification implements ShouldQueue
{
    use Queueable;

    public $requisitionForm;
    public $user;
    public $message;

    public bool $mailOnly = false;

    /**
     * Create a new notification instance.
     */
    public function __construct(RequisitionRequestForm $requisitionForm, User $user, $message)
    {
        $this->requisitionForm = $requisitionForm;
        $this->user = $user;
        $this->message = $message;
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
        $url = route('requisition_forms.view', $this->requisitionForm->id);

        return (new MailMessage)
            ->subject('Request for Further Non-Objection | PRA')
            ->markdown('emails.request-for-further-approval', [
                'requisitionForm' => $this->requisitionForm,
                'forwardedBy' => $this->user,
                'url' => $url,
                'message' => $this->message,
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
            'title' => 'Request for Further Non-Objection',
            'message' => 'A requisition form #' . $this->requisitionForm->form_code . ' has been forwarded to you by ' . $this->user->name . ' for your further approval. Message: ' . $this->message,
            'url' => route('requisition_forms.view', $this->requisitionForm->id),
        ];
    }
}
