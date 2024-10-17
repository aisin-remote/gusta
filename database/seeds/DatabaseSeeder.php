<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\User::insert([
            'department_id' => null,
            'name' => 'administrator',
            'email' => 'administrator@aiia.co.id',
            'company' => 'AISIN',
            'role' => 'visitor',
            'occupation' => 3,
            'password' => \Illuminate\Support\Facades\Hash::make('12345678')
        ]);
    }
}
