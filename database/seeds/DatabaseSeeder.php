<?php

use App\Card;
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

        $cards = Card::select('id')->get();

        foreach($cards as $card){
            for($i = 0; $i <31; $i++){
                CardStatus::create([
                    'card_id' => $card->id,
                    'serial' => $i,
                    'status' => 'ready'
                ]);
            }
        }
    }
}
