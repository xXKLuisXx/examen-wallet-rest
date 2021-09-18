<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $payment = new Payment();
        $payment->amount = 1;
        $payment->final_amount = 10;
        $payment->taxes = 9;
        $payment->payment_status_id = 3;
        $payment->service_id = 1;
        $payment->save();

        $payment->wallets()->attach(1, ['payment_type_id' => 1]);
        $payment->wallets()->attach(2, ['payment_type_id' => 3]);

        $payment = new Payment();
        $payment->amount = 12;
        $payment->final_amount = 15;
        $payment->taxes = 3;
        $payment->payment_status_id = 3;
        $payment->service_id = 1;
        $payment->save();

        $payment->wallets()->attach(1, ['payment_type_id' => 1]);
        $payment->wallets()->attach(2, ['payment_type_id' => 3]);

        $payment = new Payment();
        $payment->amount = 12;
        $payment->final_amount = 13;
        $payment->taxes = 1;
        $payment->payment_status_id = 3;
        $payment->service_id = 2;
        $payment->save();

        $payment->wallets()->attach(1, ['payment_type_id' => 1]);
        $payment->wallets()->attach(2, ['payment_type_id' => 3]);
    }
}
