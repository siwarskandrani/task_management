<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTaskUpdatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskUpdated $event)
    {
        $task = $event->task;
        $teamMembers = $task->team->users; 
    
        foreach ($teamMembers as $member) {
            if ($member->id !== $task->user_id) {
                $member->notify(new TaskUpdatedNotification($task));
            }
        }
    }
    
}
