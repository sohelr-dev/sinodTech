<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::firstOrCreate(
            ['email' => 'rahim@gmail.com'],
            ['name' => 'Rahim Kurdi', 'phone' => '01711223344', 'last_purchase_at' => now()->subDays(5)]
        );

        Customer::firstOrCreate(
            ['email' => 'karim@gmail.com'],
            ['name' => 'Karim Rahman', 'phone' => '01811223344', 'last_purchase_at' => now()->subDays(20)]
        );

        Customer::firstOrCreate(
            ['email' => 'sabbir@gmail.com'],
            ['name' => 'Sabbir Ahmed', 'phone' => '01911223344', 'last_purchase_at' => now()->subDays(95)]
        );

        Customer::firstOrCreate(
            ['email' => 'nayeem@gmail.com'],
            ['name' => 'Nayeem Islam', 'phone' => '01511223344', 'last_purchase_at' => null]
        );

        Customer::firstOrCreate(
            ['email' => 'tasnim@gmail.com'],
            ['name' => 'Tasnim Ara', 'phone' => '01611223344', 'last_purchase_at' => now()->subDays(120)]
        );
    }
}
