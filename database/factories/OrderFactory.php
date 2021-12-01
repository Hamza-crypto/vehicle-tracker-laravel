<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(1,5),
            'card_number' => $this->faker->numberBetween(4711111111111111,4799999999999999),
            'month' => rand(01,12),
//            'status' => 'declined',
            'year' => rand(21,30),
            'cvc' => rand(100,999),
            'amount' => rand(50,1000),
            'processed_by' => rand(1,5),

        ];
    }
}
