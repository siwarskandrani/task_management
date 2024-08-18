<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-task-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasks = Task::whereDate('start_date', '=', now()->addDays(2))
            ->orWhereDate('end_date', '=', now()->addDays(2))
            ->get();
    
        foreach ($tasks as $task) {
            Notification::send($task->assignedUser, new TaskReminderNotification($task));
        }
    }
    
}
