<?php

use App\Console\Commands\SendEventReminder;
use Illuminate\Support\Facades\Schedule;

// Set Send Event Reminder to Execute 15 Min Before Event Time
Schedule::command(SendEventReminder::class)->hourlyAt(45);
