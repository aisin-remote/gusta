<?php

use App\Card;
use App\User;
use App\CardStatus;
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
        // \App\User::insert([
        //     'department_id' => null,
        //     'name' => 'administrator',
        //     'email' => 'administrator@aiia.co.id',
        //     'company' => 'AISIN',
        //     'role' => 'visitor',
        //     'occupation' => 3,
        //     'password' => \Illuminate\Support\Facades\Hash::make('12345678')
        // ]);

        // $cards = Card::select('id')->get();

        User::create([
            'name' => 'Admin',
            'email' => 'administrator@aiia.co.id',
            'company' => 'AISIN',
            'role' => 'superadmin',
            'occupation' => '3',
            'email_verified_at' => '2024-11-14 14:53:25',
            'password' => '$2a$12$8h3yjotmv4BkDw1UW4srqedzFe.7W7WzxrN0Ti9hbOWcj5sbYHekG'
        ]);

        // foreach($cards as $card){
        //     for($i = 0; $i <31; $i++){
                
        //     }
        // }
    }
}
