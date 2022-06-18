<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Listing;
use Illuminate\Database\Seeder;

class ListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'Ahmed',
            'email' => 'ahmed@mail.com',
        ]);
        Listing::factory(10)->create([
            'user_id' => $user->id,
        ]);
    }
}
