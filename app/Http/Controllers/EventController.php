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

        return redirect()->route('list')->with('failure', 'Add event failed.');
    }

    public function showEventList(string $status = null): View
    {
        // Retrieve Search UUID
        $searchUuid = request()->input('searchUuid');

        // Retrieve Event Record
        $events = Event::query()
            ->when(is_null($status), function ($query) {
                // Show Upcoming Events First
                $query->where(function ($query) {
                    $query->where('date', '>', now()->toDateString())
                        ->orWhere(function ($query) {
                            $query->where('date', '=', now()->toDateString())
                                ->where('time', '>', now()->toTimeString());
                        });
                })
                    ->orderBy('date', 'asc')
                    ->orderBy('time', 'asc');

                // Then Append Completed Events
                $query->union(
                    Event::query()->where(function ($query) {
                        $query->where('date', '<', now()->toDateString())
                            ->orWhere(function ($query) {
                                $query->where('date', '=', now()->toDateString())
                                    ->where('time', '<', now()->toTimeString());
                            });
                    })
                        ->orderBy('date', 'desc')
                        ->orderBy('time', 'desc')
                );
            })
            ->when($status === EnumHelper::UPCOMING, function ($query) {
                return $query->where(function ($query) {
                    $query->where('date', '>', now()->toDateString())
                        ->orWhere(function ($query) {
                            $query->where('date', '=', now()->toDateString())
                                ->where('time', '>', now()->toTimeString());
                        });
                })
                    ->orderBy('date')
                    ->orderBy('time');
            })
            ->when($status === EnumHelper::COMPLETED, function ($query) {
                return $query->where(function ($query) {
                    $query->where('date', '<', now()->toDateString())
                        ->orWhere(function ($query) {
                            $query->where('date', '=', now()->toDateString())
                                ->where('time', '<', now()->toTimeString());
                        });
                })
                    ->orderBy('date', 'desc')
                    ->orderBy('time', 'desc');
            })
            ->when($searchUuid, function ($query) use ($searchUuid) {
                return $query->where('uuid', 'like', '%' . $searchUuid . '%'); // Search by UUID
            })
            ->paginate(10);

        return view('list', compact(['events', 'status']));
    }

    public function getEventByUuid()
    {

    }

    public function deleteEvent(string $uuid): RedirectResponse
    {
        try {
            DB::transaction(function () use ($uuid) {
                Event::query()->where('uuid', $uuid)->delete();
            });

            return redirect()->route('list')->with('success', 'Event deleted successfully.');
        } catch (\Exception $exception) {
            // Record Error
            Log::error('Error from Delete Event: ' . $exception->getMessage() . ' At: ' . $exception->getFile() . ' Line: ' . $exception->getLine());
        }

        return redirect()->route('list')->with('failure', 'Delete event failed.');
    }
}
