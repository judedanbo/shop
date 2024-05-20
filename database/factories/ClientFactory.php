<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Client;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'other_names' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'last_name' => $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}
