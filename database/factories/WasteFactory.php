<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Waste;

class WasteFactory extends Factory
{
    protected $model = Waste::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'description' => $this->faker->text(),
        ];
    }
}
