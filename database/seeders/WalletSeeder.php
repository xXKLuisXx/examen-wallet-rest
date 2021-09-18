<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wallet;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $wallet = new Wallet();
        $wallet->token = Hash::make(Carbon::now());
        $wallet->balance = 10;
        $wallet->user_id = 1;
        $wallet->save();

        $wallet = new Wallet();
        $wallet->token = Hash::make(Carbon::now());
        $wallet->balance = 10;
        $wallet->user_id = 2;
        $wallet->save();
    }
}
