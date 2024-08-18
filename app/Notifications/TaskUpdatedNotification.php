<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskUpdatedNotification extends Notification
{
    use Queueable;

    protected $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    // Définir les canaux par lesquels la notification sera envoyée
    public function via($notifiable)
    {
        return ['mail', 'database']; // pour une notification en base de données et par mail
    }

    // Configurer le message de la notification par email
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Task Updated')
                    ->line('The task titled "' . $this->task->title . '" has been updated.')
                    ->action('View Task', url('/tasks/' . $this->task->id))
                    ->line('Thank you for using our application!');
    }

    // Configurer le message de la notification en base de données (pour l'application)
    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'description' => 'The task has been updated.',
        ];
    }
}
