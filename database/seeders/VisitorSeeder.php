<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;

class VisitorSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        $purposes = ['coordination', 'audience', 'monitoring', 'meeting', 'visit', 'other'];

        for ($i = 0; $i < 50; $i++) {
            $purpose = $faker->randomElement($purposes);

            Visitor::create([
                'full_name'    => $faker->name(),
                'institution'  => $faker->company(),
                'phone_number' => '08' . $faker->numerify('#########'),
                'purpose'      => $purpose,
                'meet_with'    => $purpose === 'visit' ? $faker->name() : null,
                'notes'        => $faker->optional(0.5)->sentence(),
                'photo'        => null,
                'created_at'   => $faker->dateTimeBetween('-3 months', 'now'),
                'updated_at'   => now(),
            ]);
        }
    }
}
