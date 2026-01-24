<?php

namespace App\Notifications;

use App\Models\RequisitionRequestForm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ApprovedByCAB extends Notification implements ShouldQueue
{
    use Queueable;

    public $requisitionRequestForm;

    public bool $mailOnly = false;

    /**
     * Create a new notification instance.
     */
    public function __construct(RequisitionRequestForm $requisitionRequestForm)
    {
        $this->requisitionRequestForm = $requisitionRequestForm;
        Log::info("CAB Approval Notification sent from queue for form " . $this->requisitionRequestForm->form_code);
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
            ->subject('Requisition Form Approved by Cost & Budgeting')
            ->cc($this->requisitionRequestForm->requestingUnit->headOfDepartment->email)
            ->markdown('emails.approved-by-cab', [
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
            'title' => 'Requisition Form Approved by Cost & Budgeting',
            'message' => 'Cost & Budgeting has entered the details regarding the availability of funding for form #' . $this->requisitionRequestForm->form_code . '.',
            'url' => route('requisition_forms.view', $this->requisitionRequestForm->id),
        ];
    }
}
