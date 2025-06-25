<?php

namespace Database\Factories;

use App\Models\Url;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Url>
 */
class UrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'original_url' => $this->faker->url(),
            'short_code' => Url::generateShortCode(),
            'visits' => 0,
            'expires_at' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
            'is_active' => $this->faker->boolean(90),
            'is_public' => $this->faker->boolean(80),
            'password' => null,
            'user_id' => null, // opcional: puedes asignarlo en el seeder
        ];
    }
}
