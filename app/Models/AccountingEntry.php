<?php

namespace App\Models;

use App\Models\Sale;
use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountingEntry extends Model
{
    use HasFactory;

        protected $fillable = [
        'account_id',
        'debit_amount',
        'credit_amount',
        'description',
        'entry_date',
        'sale_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
