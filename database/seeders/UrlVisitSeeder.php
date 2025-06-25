<?php

namespace Database\Seeders;

use App\Models\Url;
use App\Models\UrlVisit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class UrlVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $urls = Url::all();

        foreach ($urls as $url) {
            UrlVisit::factory()->count(rand(3, 10))->create([
                'url_id' => $url->id,
                'ip_address' => $faker->ipv4,
                'user_agent' => $faker->userAgent,
            ]);
        }
    }
}
