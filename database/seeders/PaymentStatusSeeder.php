<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $payment_status = new PaymentStatus();
        $payment_status->name = "Pending";
        $payment_status->save();

        $payment_status = new PaymentStatus();
        $payment_status->name = "Rejected";
        $payment_status->save();

        $payment_status = new PaymentStatus();
        $payment_status->name = "Completed";
        $payment_status->save();
    }
}
