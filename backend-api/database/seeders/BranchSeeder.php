<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::create(['name' => 'Dhaka Branch', 'address' => 'Dhaka, Bangladesh']);
        Branch::create(['name' => 'Chittagong Branch', 'address' => 'Chittagong, Bangladesh']);
        Branch::create(['name' => 'Sylhet Branch', 'address' => 'Sylhet, Bangladesh']);
    }
}
