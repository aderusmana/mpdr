<?php

namespace Database\Factories\Master;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Master\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name'    => $this->faker->company,   // bisa juga $this->faker->name
            'address' => $this->faker->address,
        ];
    }
}
