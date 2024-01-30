<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Разраб',
                'email' => 'admin@admin.com',
                'phone' => '79999999',
                'password' => Hash::make('Tester1'),
                'role' => 'admin',
                'is_confirm' => 1
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate($user);
        }
    }
}
