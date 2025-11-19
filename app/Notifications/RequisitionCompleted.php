<?php

namespace App\Notifications;

use App\Models\Requisition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class RequisitionCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public $requisition;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Requisition $requisition)
    {
        $this->requisition = $requisition;
        Log::info('Requisition Completed Email for requisition ' . $this->requisition->requisition_no . ' sent from queue');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Requisition Completed | Procurement Requisition Application')
            ->markdown('emails.requisition-completed', [
                'requisition' => $this->requisition,
                'url' => route('requisitions.view', $this->requisition->id),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Requisition Completed',
            'message' => 'Requisition ' . $this->requisition->requisition_no . ' has been completed.',
            'url' => route('requisitions.view', $this->requisition->id),
        ];
    }
}
