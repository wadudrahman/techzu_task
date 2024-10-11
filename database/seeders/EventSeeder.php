<?php

namespace Database\Seeders;

use App\Helpers\EnumHelper;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initialize Faker for generating random data
        $faker = \Faker\Factory::create();

        // Define starting and ending dates
        $startDate = Carbon::now()->subDays(7); // 7 days ago from today
        $endDate = Carbon::now()->addDays(7);   // 7 days from today

        // Initialize time slots (9 AM to 6 PM)
        $timeSlots = collect([
            '09:00:00', '10:00:00', '11:00:00', '12:00:00', '13:00:00',
            '14:00:00', '15:00:00', '16:00:00', '17:00:00', '18:00:00'
        ]);

        // Loop to generate 47 records
        for ($i = 1; $i <= 47; $i++) {
            // Pick a random date between startDate and endDate
            $randomDate = $faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d');

            // Pick a random time from timeSlots
            $randomTime = $timeSlots->random();

            // Prepare Data Pool
            $data[] = [
                'uuid' => EnumHelper::EVENT_PREFIX . str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT),
                'title' => $faker->name,
                'date' => $randomDate,
                'time' => $randomTime,
                'guests' => $faker->email,
            ];
        }

        // Insert into the database
        Event::query()->insert($data);
    }
}
