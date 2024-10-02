<?php

namespace Database\Factories;

use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionDetailFactory extends Factory
{
    protected $model = TransactionDetail::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'amount' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}