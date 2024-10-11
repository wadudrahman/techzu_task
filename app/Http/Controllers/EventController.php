<?php

namespace App\Http\Controllers;

use App\Helpers\EnumHelper;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{DB, Log};
use Illuminate\View\View;

class EventController extends Controller
{
    public function showAddEvent(): View
    {
        // Retrieve Current Date Time
        $currentDateTime = Carbon::now();
        // Generate Available Dates
        $availableDates = [];
        for ($i = 0; $i < 15; $i++) {
            $availableDates[] = $currentDateTime->copy()->addDay($i)->format('d M Y');
        }
        // Generate Available Slots
        $availableSlots = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];

        return view('add', compact(['availableDates', 'availableSlots']));
    }

    public function addEvent(Request $request): RedirectResponse
    {
        // Validation Rules
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|string',
            'guests' => 'required|string|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})(;\s*[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})*$/'
        ], [
            'title.required' => 'The event title is required.',
            'date.required' => 'Please select a valid date.',
            'date.after_or_equal' => 'The selected date must be today or later.',
            'time.required' => 'Please select a time slot.',
            'guests.required' => 'The guest emails are required.',
            'guests.regex' => 'Please provide valid email addresses separated by semicolons.',
        ]);

        try {
            // DB Operation
            DB::transaction(function () use ($validated) {
                Event::query()->create([
                    'uuid' => EnumHelper::EVENT_PREFIX . Carbon::now()->timestamp,
                    'title' => trim($validated['title']),
                    'date' => Carbon::createFromFormat('d M Y', trim($validated['date']))->format('Y-m-d'),
                    'time' => trim($validated['time']),
                    'guests' => trim($validated['guests']),
                ]);
            });

            return redirect()->route('list')->with('success', 'Event added successfully.');
        } catch (\Exception $exception) {
            // Record Error
            Log::error('Error from Add Event: ' . $exception->getMessage() . ' At: ' . $exception->getFile() . ' Line: ' . $exception->getLine());
        }

        return redirect()->route('list')->with('failure', 'Event added successfully.');
    }

    public function showEventList(string $status = null): View
    {
        // Retrieve Event Record
        $currentDateTime = Carbon::now();
        $events = Event::query()
            ->when(is_null($status), function ($query) {
                return $query;
            })
            ->when($status === EnumHelper::UPCOMING, function ($query) use ($currentDateTime) {
                return $query->where('date', '>=', $currentDateTime->format('Y-m-d'))
                    ->where('time', '>=', $currentDateTime->format('H:i'));
            })
            ->when($status === EnumHelper::COMPLETED, function ($query) use ($currentDateTime) {
                return $query->where('date', '<=', $currentDateTime->format('Y-m-d'))
                    ->where('time', '<=', $currentDateTime->format('H:i'));
            })
            ->paginate(10);

        return view('list', compact(['events']));
    }
}
