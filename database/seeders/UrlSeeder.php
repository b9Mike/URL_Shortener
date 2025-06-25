<?php

namespace Database\Seeders;

use App\Models\Url;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Url::factory()->count(3)->create([
                'user_id' => $user->id
            ]);
        }

        // Algunas URLs sin usuario
        Url::factory()->count(2)->create([
            'user_id' => null
        ]);
    }
}
