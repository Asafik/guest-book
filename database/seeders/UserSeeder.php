<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin Dinas',
            'email'    => 'admin@jcc.go.id',
            'role'     => 'super_admin',
            'password' => Hash::make('password'),
        ]);
    }
}
