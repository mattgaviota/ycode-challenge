<?php

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert([
            'balance' => 15000,
            'currency' => 'USD',
            'name' => 'John',
        ]);

        DB::table('accounts')->insert([
            'balance' => 100000,
            'currency' => 'USD',
            'name' => 'Peter',
        ]);
    }
}
