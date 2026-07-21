<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::firstOrCreate(['name' => 'Dhaka Branch'], ['address' => 'Dhaka, Bangladesh']);
        Branch::firstOrCreate(['name' => 'Chittagong Branch'], ['address' => 'Chittagong, Bangladesh']);
        Branch::firstOrCreate(['name' => 'Sylhet Branch'], ['address' => 'Sylhet, Bangladesh']);
    }
}
