<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\RequisitionRequestForm;
use App\Models\User;

class ForwardForm extends Notification
{
    use Queueable;

    public $requisitionForm;
    public $forwardedBy;
    public $forwarding_minute;

    /**
     * Create a new notification instance.
     */
    public function __construct(RequisitionRequestForm $requisitionForm, User $forwardedBy, $forwarding_minute)
    {
        $this->requisitionForm = $requisitionForm;
        $this->forwardedBy = $forwardedBy;
        $this->forwarding_minute = $forwarding_minute;
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
        $url = route('requisition_forms.view', $this->requisitionForm->id);

        return (new MailMessage)
            ->subject('Requisition Form Forwarded')
            ->markdown('emails.forward-form', [
                'requisitionForm' => $this->requisitionForm,
                'url' => $url,
                'forwardedBy' => $this->forwardedBy,
                'forwarding_minute' => $this->forwarding_minute,
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
            "title" => "Requisition Form Forwarded by " . ($this->forwardedBy->reporting_officer_role ?? $this->forwardedBy->name),
            "message" => "The requisition form #" . $this->requisitionForm->form_code . " has been forwarded to you. Minute: " . $this->forwarding_minute,
            "url" => route('requisition_forms.view', $this->requisitionForm->id),
        ];
    }
}
