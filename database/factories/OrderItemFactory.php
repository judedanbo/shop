<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Price;
use App\Models\Waste;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'origin' => $this->faker->word(),
            'source' => $this->faker->word(),
            'order_id' => Order::factory(),
            'waste_id' => Waste::factory(),
            'price_id' => Price::factory(),
        ];
    }
}
