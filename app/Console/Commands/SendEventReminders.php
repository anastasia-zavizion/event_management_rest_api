<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\EventReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends all notifications to event attendeess';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = Event::with('attendees.user')->whereBetween('start_time', [now(), now()->addDay()])->get();
        $eventsCount = $events->count();
        $eventLabels = Str::plural('event', $eventsCount);

        $events->each(fn($event) => $event->attendees->each(fn($attendee) => $attendee->user->notify(new EventReminderNotification($event))));

        $this->info("Notificattion sent successfully: $eventsCount $eventLabels");
    }
}
