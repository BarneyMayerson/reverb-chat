<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $groupOne = User::factory(5)->create();
        $groupTwo = User::factory(5)->create();

        $dev = User::factory()->create([
            'name' => 'Barney Mayerson',
            'email' => 'mr.barney.mayerson@gmal.com',
            'password' => bcrypt('1234qwer'),
        ]);

        Chat::factory()->create([
            'initiator_id' => $dev->id,
            'recipient_id' => fake()->randomElement($groupOne->pluck('id')),
        ]);
        Chat::factory()->create([
            'initiator_id' => fake()->randomElement($groupTwo->pluck('id')),
            'recipient_id' => $dev->id,
        ]);
    }
}
