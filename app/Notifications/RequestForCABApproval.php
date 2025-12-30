<?php

namespace App\Notifications;

use App\Models\RequisitionRequestForm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class RequestForCABApproval extends Notification implements ShouldQueue
{
    use Queueable;

    public $requisitionRequestForm;

    /**
     * Create a new notification instance.
     */
    public function __construct(RequisitionRequestForm $requisitionRequestForm)
    {
        $this->requisitionRequestForm = $requisitionRequestForm;
        Log::info('Notification sent to CAB for Requisition Request Form ' . $this->requisitionRequestForm->form_code . ' from queue');
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
        return (new MailMessage)
            ->subject('Requisition Form for Funding Availability | PRA')
            ->markdown('emails.request-for-cab-approval', [
                'url' => route('requisition_forms.view', $this->requisitionRequestForm->id),
                'form' => $this->requisitionRequestForm
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
            'title' => 'Requisition Form sent for Funding Availability',
            'message' => 'A new requisition form requires your attention to determine the availability of funding.',
            'url' => route('requisition_forms.view', $this->requisitionRequestForm->id),
        ];
    }
}
