<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionDetail;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::factory()
            ->count(50)
            ->create()
            ->each(function ($transaction) {
                TransactionDetail::factory()
                    ->count(1) // Buat hanya satu detail per transaksi
                    ->create([
                        'transaction_id' => $transaction->id,
                    ]);
            });
    }
}