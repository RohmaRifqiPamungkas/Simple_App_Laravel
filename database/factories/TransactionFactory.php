<?php

// database/factories/TransactionFactory.php
namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->bothify('TR######'),
            'description' => $this->faker->sentence,
            'rate' => $this->faker->randomFloat(2, 1, 100),
            'date_paid' => $this->faker->date(),
            'category' => $this->faker->randomElement(['Income', 'Expense']), // Tambahkan category
        ];
    }
}