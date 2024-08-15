<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InvoicePaid extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
                    ->subject('Rappel : Date limite de la tâche approchant')
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('La tâche "' . $this->task->title . '" a une date limite dans deux jours.')
                    ->line('Date limite : ' . $this->task->end_date->format('d-m-Y'))
                    ->action('Voir la tâche', url('/tasks/' . $this->task->id))
                    ->line('Merci de prendre les mesures nécessaires avant la date limite.');
    }
}
