<?php

namespace App\Models;

use App\Models\SaleItem;
use App\Models\AccountingEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

        protected $fillable = [
        'sale_date',
        'total_amount',
        'discount_amount',
        'vat_amount',
        'paid_amount',
        'due_amount',
        'status'
    ];
    protected $casts = [
        'sale_date' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function accountingEntries()
    {
        return $this->hasMany(AccountingEntry::class);
    }
}
