<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database or prepare necessary data
        $this->artisan('migrate');
    }

    #[Test]
    public function it_can_show_the_transactions_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // Seed some transaction data
        Transaction::factory()->count(5)->create();

        $response = $this->actingAs($user)->get(route('transactions.index'));

        $response->assertStatus(200);
        $response->assertSee('Transactions');
    }

    #[Test]
    public function it_can_show_the_transaction_create_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('transactions.create'));

        $response->assertStatus(200);
        $response->assertSee('Create Transaction');
    }

    #[Test]
    public function it_can_create_a_transaction()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $transactionData = [
            'code' => 'TR123456',
            'description' => 'Sample transaction',
            'rate' => 100.00,
            'date_paid' => '2023-10-10',
            'category' => 'Income', // Tambahan field category
            'details' => [
                ['name' => 'Detail 1', 'amount' => 50.00],
                ['name' => 'Detail 2', 'amount' => 50.00],
            ]
        ];

        $response = $this->actingAs($user)->post(route('transactions.store'), $transactionData);

        $response->assertStatus(302); // Redirection status
        $response->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas('transactions', [
            'code' => 'TR123456',
            'description' => 'Sample transaction',
            'rate' => 100.00,
            'date_paid' => '2023-10-10',
            'category' => 'Income', // Pastikan field category dicek
        ]);

        $this->assertDatabaseHas('transaction_details', [
            'name' => 'Detail 1',
            'amount' => 50.00,
        ]);

        $this->assertDatabaseHas('transaction_details', [
            'name' => 'Detail 2',
            'amount' => 50.00,
        ]);
    }
}