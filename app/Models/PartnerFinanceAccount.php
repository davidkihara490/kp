<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerFinanceAccount extends Model
{
     protected $fillable = [
        'partner_id',
        'bank_account_number',
        'bank_account_name',
        'bank_name',
        'bank_branch',
        'finance_email',
    ];

    public function partner(){
        return $this->belongsTo(Partner::class);
    }
}
