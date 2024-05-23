<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Payment;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->numberBetween(-10000, 10000),
            'date' => $this->faker->date(),
            'payment_method' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'phone' => $this->faker->phoneNumber(),
            'transaction_id' => $this->faker->regexify('[A-Za-z0-9]{15}'),
            'comments' => $this->faker->word(),
            'order_id' => Order::factory(),
        ];
    }
}
