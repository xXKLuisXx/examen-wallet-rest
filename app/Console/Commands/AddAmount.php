<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Payment;

class AddAmount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:addamount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'increase in 1 value of payments actives and service launch marketing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Payment::all()->where('payment_status_id', '2')->where('service_id',3)->values()->map(function($payment){
            //$walletSupplier = $payment->wallets
            $payment->final_amount += 1;
            $payment->save();

            $wallet = $payment->wallets->map(function($wallet){
                if($wallet->pivot->type->name = "Expenditure"){
                    return $wallet;
                }
            })->first();

            if($wallet->balance >= $payment->amount){
                $payment->wallets->map(function($wallet) use ($payment){
                    if($wallet->pivot->type->id == 1){// es recarga
                        $wallet->balance += $payment->amount;
                    }else if($wallet->pivot->type->id == 2){ // estas pagando
                        $wallet->balance += $payment->amount * - 1;
                    }else if($wallet->pivot->type->id == 3){ // recibes comisiones
                        $wallet->balance += $payment->taxes;
                    }
                    $wallet->save();
                    return $wallet;
                });
            }else{
                $payment->payment_status_id = 4; //completado
                $payment->save();
            }
            return $payment;
        });
        $texto = "[" . date('Y-m-d H:i:s') . "] Mi nombre es Luis";
        Storage::append("archivo.txt", $texto);
        //return 0;
    }
}
