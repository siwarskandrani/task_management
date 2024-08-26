<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDeletedNotification extends Notification
{
    use Queueable;

    public $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Ajoutez 'database' si vous souhaitez stocker les notifications dans la base de données
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Tâche supprimée')
                    ->line("La tâche '{$this->task->title}'a été supprimée.")
                    ->action('Voir les tâches', url('/tasks'))
                    ->line('Merci d\'utiliser notre application!');
    }

    public function toArray($notifiable)
    {
        return [
            'task_title' => $this->task->title,
        ];
    }
}
