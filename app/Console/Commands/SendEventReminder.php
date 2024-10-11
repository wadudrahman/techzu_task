<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEventReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:event-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for upcoming events 15 minutes before they start';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Retrieve and Process Current Time
        $currentDateTime = Carbon::now()->minute(0)->second(0);
        $cutOffTime = $currentDateTime->copy()->addHour();

        // Retrieve Records
        $events = Event::query()->where('date', $currentDateTime->toDateString())
            ->where('time', '>', $currentDateTime->toTimeString())
            ->where('time', '<=', $cutOffTime->toTimeString())
            ->get();

        foreach ($events as $event) {
            // Send Reminder
            $this->sendReminderEmails($event);
        }

        $this->info('Event reminder emails sent successfully.');
    }

    private function sendReminderEmails($event): void
    {
        $guests = explode(';', $event->guests);

        foreach ($guests as $guest) {
            $guest = trim($guest);

            // Send Email
            Mail::raw("Reminder: Your event '{$event->title}' is scheduled to start at {$event->time}.", function ($message) use ($guest, $event) {
                $message->to($guest)
                    ->subject("Reminder: Upcoming Event - {$event->title}");
            });
        }
    }
}
