<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Payment;
class Service extends Model
{
    use HasFactory;

    public function payments(){
        return $this->hasMany(Payment::class);
    }
}
