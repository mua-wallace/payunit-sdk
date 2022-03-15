<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
// use Illuminate\Support\Facades\Hash;

class UsersTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'wallice',
            'email' => 'wallice@gmail.com',
            'password' => Hash::make('password'),
            'remember_tokeken' => str_random(10),
        ])
    }
}
