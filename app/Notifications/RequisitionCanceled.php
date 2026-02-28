<?php

namespace App\Notifications;

use App\Models\Requisition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class RequisitionCanceled extends Notification
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
        Log::info('Requisition Canceled notification created for Requisition ' . $this->requisition->requisition_no);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = 'Requisition Canceled | ' . $this->requisition->requisition_no . ' | Procurement Requisition Application';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Good day ' . $notifiable->name . ',')
            ->line('Requisition #' . $this->requisition->requisition_no . ' has been canceled.')
            ->line('**Reason for Cancelation:**')
            ->line($this->requisition->cancelation_reason ?? 'No reason provided')
            ->line('**Requesting Unit:** ' . $this->requisition->department->name)
            ->line('**Item:** ' . $this->requisition->item)
            ->action('View Requisition', route('requisitions.view', $this->requisition->id))
            ->line('If you have any questions, please contact the Procurement Unit.');
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
            'title' => 'Requisition Canceled',
            'message' => 'Requisition #' . $this->requisition->requisition_no . ' has been canceled. Reason: ' . $this->requisition->cancelation_reason,
            'url' => route('requisitions.view', $this->requisition->id),
        ];
    }
}
