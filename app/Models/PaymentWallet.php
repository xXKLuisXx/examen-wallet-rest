<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\PaymentType;


class PaymentWallet extends Pivot
{
    public function type(){
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    } 
}
