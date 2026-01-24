<?php

namespace App\Notifications;

use App\Models\Requisition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CostBudgetingCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public $requisition;

    public bool $mailOnly = false;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Requisition $requisition)
    {
        $this->requisition = $requisition;
        Log::info('Cost & Budgeting Completed Email for requisition ' . $this->requisition->requisition_no . ' sent from queue');
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->mailOnly ? ['mail'] : ['mail', 'database'];
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
            ->subject('Requisition Completed by Cost & Budgeting | PRA')
            ->markdown('emails.cost-budgeting-completed', [
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
            'title' => 'Requisition Completed by Cost & Budgeting',
            'message' => 'Requisition #' . $this->requisition->requisition_no . ' has been completed by Cost & Budgeting.',
            'url' => route('requisitions.view', $this->requisition->id),
        ];
    }
}
