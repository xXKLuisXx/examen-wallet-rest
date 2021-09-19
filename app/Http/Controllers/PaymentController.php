<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Models\Wallet;

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
        return Auth::user()->wallet->payments->where('payment_status_id', 2);
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
                $rules = [                              
                    //'method' => 'required|string',
                    'isActive' => 'required|bool',
                    //'service_id' => 'required|numeric'
                ];
                //dd($decodedRequest);
                $validator = Validator::make($data, $rules);                        //Define a Validator for the Data
                if($validator->passes()){
                    if($data['isActive']){ // si es verdadero hay que crear un nuevo pago
                        $paymentFound = Auth::user()->wallet->payments->where('payment_status_id', 2)->first();
                        if($paymentFound){
                            return response()->json(['errors' => 'service is already active'],400);
                        }else {
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
                        }
                    }else{
                        $paymentFound = Auth::user()->wallet->payments->where('payment_status_id', 2)->first();
                        if($paymentFound){
                            $paymentFound->payment_status_id = 4;
                            $paymentFound->save();

                            return $paymentFound;
                        }else {
                            return response()->json(['errors' => 'there is no active service to cancel'],400);
                        }
                    }
                }else {
                    return $validator->errors();
                }
            }else { // service id no valido
                return response()->json(["errors" => "invalid service_id"]);
            }

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
