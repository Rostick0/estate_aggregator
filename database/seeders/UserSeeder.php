<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'password' => Hash::make('digitaldada@2'),
                'role' => 'admin'
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate($user);
        }
    }
}
