<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccountTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }

    /**
     * Get Account test
     *
     * @return void
     */
    public function testGetAccount()
    {
        $response = $this->getJson('/api/accounts/1');
        $response
            ->assertStatus(200)
            ->assertJson([
                "id" => 1,
                "currency" => "USD",
                "balance" => "15000",
                "name" => "John"
            ])
        ;
    }

    /**
     * Get Account transactions test
     *
     * @return void
     */
    public function testGetTransactionsAccount()
    {
        $response = $this->getJson('/api/accounts/1/transactions');
        $response
            ->assertStatus(200)
            ->assertJson([
                [
                    "id" => 3,
                    "from" => 2,
                    "to" => 1,
                    "details" => "sample transaction 3",
                    "amount" => "15"
                ],
                [
                    "id" => 2,
                    "from" => 1,
                    "to" => 2,
                    "details" => "sample transaction 2",
                    "amount" => "24"
                ],
                [
                    "id" => 1,
                    "from" => 1,
                    "to" => 2,
                    "details" => "sample transaction",
                    "amount" => "14"
                ]
            ])
        ;
    }

    /**
     * Create Account test
     *
     * @return void
     */
    public function testCreateAccount()
    {
        $response = $this->postJson('/api/accounts', [
            'name' => 'Test',
            'currency' => 'USD'
        ]);
        $response->assertStatus(201);
    }

    /**
     * Make a transaction
     *
     * @return void
     */
    public function testPostTransaction()
    {
        // Test the post transaction
        $response = $this->postJson('/api/accounts/1/transactions', [
            "amount" => 500,
            "details" => "Sample transaction of 500",
            "to" => 2
        ]);
        $response
            ->assertStatus(201)
            ->assertJson([
                "message" => 'Transaction was successful'
            ])
        ;
        // Check the new balance of the account 1 and 2
        $response = $this->getJson('/api/accounts/1');
        $response
            ->assertStatus(200)
            ->assertJson(["balance" => "14500"])
        ;
        $response = $this->getJson('/api/accounts/2');
        $response
            ->assertStatus(200)
            ->assertJson(["balance" => "100500"])
        ;
        // Check the new transaction
        $response = $this->getJson('/api/accounts/1/transactions');
        $response
            ->assertStatus(200)
            ->assertJson([
                [
                    "id" => 4,
                    "from" => 1,
                    "to" => 2,
                    "amount" => "500"
                ]
            ])
        ;
    }

    /**
     * Get wrong Account test
     *
     * @return void
     */
    public function testGetWrongAccount()
    {
        $response = $this->getJson('/api/accounts/3');
        $response
            ->assertStatus(404)
            ->assertJson(["message" => "Not Found!"])
        ;
    }

    /**
     * Get wrong Account transactions test
     *
     * @return void
     */
    public function testGetWrongAccountTransactions()
    {
        $response = $this->getJson('/api/accounts/3/transactions');
        $response
            ->assertStatus(404)
            ->assertJson(["message" => "Not Found!"])
        ;
    }

    /**
     * Create Wrong Account test
     *
     * @return void
     */
    public function testCreateWrongAccount()
    {
        $response = $this->postJson('/api/accounts', [
            'name' => 'Test',
            'currency' => 'US'
        ]);
        $response->assertStatus(422)
            ->assertJson([
                "currency" => ['The selected currency is invalid.']
            ])
        ;
    }

    /**
     * Make a wrong transaction
     *
     * Missing argument
     *
     * @return void
     */
    public function testPostWrongTransactionMissingArgument()
    {
        // Test the post transaction
        $response = $this->postJson('/api/accounts/1/transactions', [
            "amount" => 500,
            "details" => "Sample transaction of 500",
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                "to" => ['The to field is required.']
            ])
        ;
    }

    /**
     * Make a wrong transaction
     *
     * Exceed balance
     *
     * @return void
     */
    public function testPostWrongTransactionExceedBalance()
    {
        // Test the post transaction
        $response = $this->postJson('/api/accounts/1/transactions', [
            "amount" => 500000,
            "details" => "Sample transaction of 500000",
            "to" => 2
        ]);
        $response
            ->assertStatus(409)
            ->assertJson([
                "message" => 'The amount to transfer exceeds your balance'
            ])
        ;
    }

    /**
     * Make a wrong transaction
     *
     * Wrong destination
     *
     * @return void
     */
    public function testPostWrongTransactionWrongDestination()
    {
        // Test the post transaction
        $response = $this->postJson('/api/accounts/1/transactions', [
            "amount" => 500,
            "details" => "Sample transaction of 500",
            "to" => 3
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                "to" => ['The destination account does not exists']
            ])
        ;
    }
}
