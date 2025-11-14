<?php

namespace App\Notifications;

use App\Models\Requisition;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FundsCommitted extends Notification
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
        $date_sent = Carbon::now()->format('F jS, Y');
        $procurement_officer = $this->requisition->procurement_officer;
        $maryann = User::where('username', 'maryann.basdeo')->first();

        $mail = (new MailMessage)
            ->subject('Funds Committed for Requisition #' . $this->requisition->requisition_no)
            ->markdown('emails.funds-committed', [
                'requisition' => $this->requisition,
                'date_sent' => $date_sent,
                'url' => route('requisitions.view', $this->requisition->id),
            ]);

        if ($maryann && $procurement_officer && $procurement_officer->id !== $maryann->id) {
            $mail->cc($maryann->email);
        }

        return $mail;
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
            'title' => 'Funds Committed by Vote Control',
            'message' => 'Funds have been committed for Requisition #' . $this->requisition->requisition_no,
            'url' => route('requisitions.view', $this->requisition->id),
        ];
    }
}