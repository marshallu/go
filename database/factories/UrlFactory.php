<?php

namespace Database\Factories;

use App\Models\Url;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Url>
 */
class UrlFactory extends Factory
{
    protected $model = Url::class;

    public function definition(): array
    {
        $possibleCharacters = 'abcdefghijkmnopqrstuvwxyz234567890';
        $id = substr(str_shuffle($possibleCharacters), 0, 6);

        return [
            'id' => $id,
            'base_url' => 'https://www.marshall.edu/'.fake()->slug(),
            'long_url' => 'https://www.marshall.edu/'.fake()->slug(),
            'utm_source' => null,
            'utm_medium' => null,
            'utm_campaign' => null,
            'redirect_count' => 0,
            'last_redirected_at' => now(),
            'user_id' => User::factory(),
        ];
    }

    public function withUtm(): static
    {
        return $this->state(fn (array $attributes) => [
            'utm_source' => 'salesforce',
            'utm_medium' => 'email',
            'utm_campaign' => 'spring2024',
            'long_url' => $attributes['base_url'].'?utm_source=salesforce&utm_medium=email&utm_campaign=spring2024',
        ]);
    }

    public function old(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_redirected_at' => now()->subYears(3),
        ]);
    }

    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_redirected_at' => now()->subDays(7),
        ]);
    }
}
