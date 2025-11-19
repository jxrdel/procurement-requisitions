<?php

namespace App\Notifications;

use App\Models\RequisitionRequestForm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovedByCAB extends Notification
{
    use Queueable;

    public $requisitionRequestForm;

    /**
     * Create a new notification instance.
     */
    public function __construct(RequisitionRequestForm $requisitionRequestForm)
    {
        $this->requisitionRequestForm = $requisitionRequestForm;
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
