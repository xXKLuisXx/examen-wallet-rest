<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PaymentWallet;

class PaymentType extends Model
{
    use HasFactory;
    public function payment_wallet(){
        return $this->hasMany(PaymentWallet::class);
    }
}
