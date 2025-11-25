<?php

namespace App\Notifications;

use App\Models\Requisition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NotifyCostBudgeting extends Notification implements ShouldQueue
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
        Log::info('Notification sent to Cost & Budgeting for Requisition ' . $this->requisition->requisition_no . ' from queue');
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
        $subject = '';
        if ($this->requisition->file_no === null) {
            $subject = 'Incoming Requisition | Procurement Requisition Application';
        } else {
            $subject = 'Incoming Requisition | ' . $this->requisition->file_no .  ' | Procurement Requisition Application';
        }

        return (new MailMessage)
            ->subject($subject)
            ->markdown('emails.sent-to-cost-budgeting', [
                'requisition' => $this->requisition,
                'url' => route('queue.requisition.view', $this->requisition->cost_budgeting_requisition->id),
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
            'title' => 'Incoming Requisition',
            'message' => 'Requisition #' . $this->requisition->requisition_no . ' has been sent to Cost & Budgeting.',
            'url' => route('queue.requisition.view', $this->requisition->cost_budgeting_requisition->id),
        ];
    }
}
