<?php

namespace App\Models;

use App\Models\AccountingEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;
        protected $fillable = [
        'name',
        'type',
        'balance'
    ];

    public function entries()
    {
        return $this->hasMany(AccountingEntry::class);
    }
}
