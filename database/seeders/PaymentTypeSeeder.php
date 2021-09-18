<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentType;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $payment_type = new PaymentType();
        $payment_type->name = "Income";
        $payment_type->save();

        $payment_type = new PaymentType();
        $payment_type->name = "Expenditure";
        $payment_type->save();

        $payment_type = new PaymentType();
        $payment_type->name = "Profits";
        $payment_type->save();
    }
}
