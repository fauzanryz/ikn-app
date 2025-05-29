<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\UserModel;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        UserModel::create([
            'name' => 'ikn',
            'email' => 'ikn@gmail.com',
            'password' => Hash::make('ikn12345'),
        ]);
    }
}
