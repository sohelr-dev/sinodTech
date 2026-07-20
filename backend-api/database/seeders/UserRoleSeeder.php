<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {

        // Manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'name' => 'Manager',
                'password' => bcrypt('password'),
                'role' => 'manager',
            ]
        );
        $manager->assignRole('manager');

        // Sales
        $sales = User::firstOrCreate(
            ['email' => 'sales@gmail.com'],
            [
                'name' => 'Sales',
                'password' => bcrypt('password'),
                'role' => 'sales',
            ]
        );
        $sales->assignRole('sales');

        // Editor
        $editor = User::firstOrCreate(
            ['email' => 'editor@gmail.com'],
            [
                'name' => 'Editor',
                'password' => bcrypt('password'),
                'role' => 'editor',
            ]
        );
        $editor->assignRole('editor');

    }
}
