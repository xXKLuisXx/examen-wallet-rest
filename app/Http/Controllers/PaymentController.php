<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Payment::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $rules = [                              
            //'method' => 'required|string',
            //'amount' => 'required|numeric',
            'service_id' => 'required|numeric'
        ];
        //dd($decodedRequest);
        $validator = Validator::make($data, $rules);                        //Define a Validator for the Data
        if($validator->passes()){
            //Auth::user()->wallet->balance += $data['amount'];
            $payment = new Payment();


            if($data['service_id'] == 1){//es recarga
                $rules = [                              
                    //'method' => 'required|string',
                    'amount' => 'required|numeric',
                    //'service_id' => 'required|numeric'
                ];
                //dd($decodedRequest);
                $validator = Validator::make($data, $rules);                        //Define a Validator for the Data
                if($validator->passes()){
                    $payment->amount = $data['amount'];
                    $payment->taxes = $payment->amount * 0.02;
                    $payment->final_amount = $payment->amount + $payment->taxes;
                    $payment->payment_status_id = 4; // completado
                    $payment->service_id = $data['service_id'];
                    $payment->save();

                    $payment->wallets()->attach(Auth::user()->wallet->id,['payment_type_id' => 1]);
                    $payment->wallets()->attach(User::where('rol_id', 2)->first()->wallet->id,['payment_type_id' => 3]);
                }else{
                    return $validator->errors();
                }
            }else if($data['service_id'] == 2){ //es agregar producto
                $payment->amount = 4;
                $payment->taxes = $payment->amount * 0.02;
                $payment->final_amount = $payment->amount + $payment->taxes;
                $payment->payment_status_id = 1; // pendiente
                $payment->service_id = $data['service_id'];
                $payment->save();

                if(Auth::user()->wallet->balance >= $payment->final_amount){
                    $payment->payment_status_id = 4; // completado
                    $payment->save();
                }else{
                    $payment->payment_status_id = 3; // rechazado
                    $payment->save();
                    return response()->json(["error" => "insufficient founds"],400);
                }
                $payment->wallets()->attach(Auth::user()->wallet->id,['payment_type_id' => 2]);
                $payment->wallets()->attach(User::where('rol_id', 2)->first()->wallet->id,['payment_type_id' => 1]);
            }else if($data['service_id'] == 3){ // es lanzar una campaña de marketing
                $payment->amount = 1;
                $payment->taxes = $payment->amount * 0.02;
                $payment->final_amount = $payment->amount + $payment->taxes;
                $payment->payment_status_id = 1; // pendiente
                $payment->service_id = $data['service_id'];
                $payment->save();

                if(Auth::user()->wallet->balance >= $payment->final_amount){
                    $payment->payment_status_id = 2; // activo
                    $payment->save();
                }else{
                    $payment->payment_status_id = 3; // rechazado
                    $payment->save();
                    return response()->json(["error" => "insufficient founds"],400);
                }
                $payment->wallets()->attach(Auth::user()->wallet->id,['payment_type_id' => 2]);
                $payment->wallets()->attach(User::where('rol_id', 2)->first()->wallet->id,['payment_type_id' => 1]);
            }else { // service id no valido
                return response()->json(["errors" => "invalid service_id"]);
            }

            $payment->wallets->map(function($wallet) use ($payment){
                if($wallet->pivot->type->id == 1){
                    $wallet->balance += $payment->amount;
                }else if($wallet->pivot->type->id == 2){
                    $wallet->balance += $payment->final_amount * - 1;
                }else if($wallet->pivot->type->id == 3){
                    $wallet->balance += $payment->taxes;
                }
                $wallet->save();
                return $wallet;
            });

            return $payment->wallets->where('user_id', Auth::user()->id)->first();
        }

        return $validator->errors();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
