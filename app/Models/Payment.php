<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Wallet;
use App\Models\Service;
use App\Models\PaymentStatus;
use App\Models\PaymentWallet;

class Payment extends Model
{
    use HasFactory;

    public function wallets(){
        return $this->belongsToMany(Wallet::class)->using(PaymentWallet::class)->withPivot('payment_type_id');
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function status(){
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }
}
