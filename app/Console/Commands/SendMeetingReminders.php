<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendMeetingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-meeting-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automatic meeting reminders to participants 1 day before the event.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = \Carbon\Carbon::tomorrow()->toDateString();
        $reunions = \App\Models\Reunion::whereDate('date', $tomorrow)
            ->where('status', 'planifiee')
            ->with('participants')
            ->get();

        foreach ($reunions as $reunion) {
            foreach ($reunion->participants as $participant) {
                // Email
                \Illuminate\Support\Facades\Mail::to($participant->email)
                    ->send(new \App\Mail\MeetingReminderMail($reunion, $participant));
                
                // Database Notification
                $participant->notify(new \App\Notifications\MeetingNotification([
                    'title' => 'Rappel : Réunion demain',
                    'message' => "Rappel : La réunion \"{$reunion->titre}\" a lieu demain à {$reunion->date->format('H:i')}.",
                    'action_url' => route('reunions.show', $reunion),
                    'type' => 'reminder'
                ]));
            }
            $this->info("Reminders sent for: {$reunion->titre}");
        }

        return 0;
    }
}
