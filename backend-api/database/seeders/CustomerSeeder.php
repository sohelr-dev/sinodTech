<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::create([
            'name' => 'Rahim Kurdi',
            'email' => 'rahim@gmail.com',
            'phone' => '01711223344',
            'last_purchase_at' => now()->subDays(5)
        ]);

        Customer::create([
            'name' => 'Karim Rahman',
            'email' => 'karim@gmail.com',
            'phone' => '01811223344',
            'last_purchase_at' => now()->subDays(20)
        ]);

        Customer::create([
            'name' => 'Sabbir Ahmed',
            'email' => 'sabbir@gmail.com',
            'phone' => '01911223344',
            'last_purchase_at' => now()->subDays(95)
        ]);

        Customer::create([
            'name' => 'Nayeem Islam',
            'email' => 'nayeem@gmail.com',
            'phone' => '01511223344',
            'last_purchase_at' => null
        ]);
        
        Customer::create([
            'name' => 'Tasnim Ara',
            'email' => 'tasnim@gmail.com',
            'phone' => '01611223344',
            'last_purchase_at' => now()->subDays(120)
        ]);
    }
}
