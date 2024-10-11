<?php

namespace App\Http\Controllers;

use App\Helpers\EnumHelper;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{DB, Log, Validator};
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportController extends Controller
{
    public function downloadSample(): BinaryFileResponse
    {
        return response()->download(public_path('file/sample.csv'));
    }

    public function showCsvImport(): View
    {
        return view('import');
    }

    public function csvImport(Request $request): RedirectResponse
    {
        // Validate File
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $file = $request->file('file');
            $filePath = $file->getRealPath();

            // Read the CSV
            $data = [];
            if (($handle = fopen($filePath, "r")) !== FALSE) {
                while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $data[] = $row;
                }
                fclose($handle);
            }

            // Validate and process the data
            $headers = array_shift($data);
            $processedData = [];
            $index = 0;
            foreach ($data as $row) {
                $validatedData = $this->validateRow($row, $headers);
                if ($validatedData) {
                    // Prepare Processed Data for Import
                    $processedData[] = [
                        'uuid' => EnumHelper::EVENT_PREFIX . Carbon::now()->timestamp . $index,
                        'title' => $validatedData['title'],
                        'date' => $validatedData['date'],
                        'time' => $validatedData['time'],
                        'guests' => $validatedData['guests']
                    ];
                }

                // Increase Index
                $index++;
            }

            // DB Operation
            if (!empty($processedData)) {
                DB::beginTransaction();
                Event::query()->insert($processedData);
                DB::commit();
            }

            return redirect()->route('list')->with('success', 'Event imported successfully.');
        } catch (\Exception $exception) {
            // DB Rollback
            DB::rollBack();

            // Log Error
            Log::error('Error while importing event: ' . $exception->getMessage(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
        }

        return redirect()->back()->with('failure', 'CSV data import failed.');
    }

    private function validateRow(array $row, array $headers): false|array
    {
        // Validate
        $validator = Validator::make(array_combine($headers, $row), [
            'title' => 'required|string|max:255',
            'date' => 'required|date|date_format:Y-m-d',
            'time' => 'required|string|date_format:H:i',
            'guests' => 'required|string|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})(;\s*[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})*$/'
        ]);

        // Skip Invalid Entries
        if ($validator->fails()) {
            return false;
        }

        return array_combine($headers, $row);
    }
}
