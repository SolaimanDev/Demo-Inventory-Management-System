<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountingEntry;
use App\Models\Sale;

class AccountingService
{
    public function createSaleEntries(Sale $sale)
    {
        // Get or create necessary accounts
        $cashAccount = Account::firstOrCreate(
            ['name' => 'Cash'],
            ['type' => 'asset', 'balance' => 0]
        );
        
        $receivableAccount = Account::firstOrCreate(
            ['name' => 'Accounts Receivable'],
            ['type' => 'asset', 'balance' => 0]
        );
        
        $salesAccount = Account::firstOrCreate(
            ['name' => 'Sales'],
            ['type' => 'revenue', 'balance' => 0]
        );
        
        $vatAccount = Account::firstOrCreate(
            ['name' => 'VAT Payable'],
            ['type' => 'liability', 'balance' => 0]
        );
        
        $discountAccount = Account::firstOrCreate(
            ['name' => 'Discounts'],
            ['type' => 'expense', 'balance' => 0]
        );
        
        // Calculate amounts
        $subtotal = $sale->total_amount - $sale->vat_amount;
        $netAfterDiscount = $subtotal - $sale->discount_amount;
        
        // Create entries
        $entries = [];
        
        // If paid amount > 0, debit cash
        if ($sale->paid_amount > 0) {
            $entries[] = [
                'account_id' => $cashAccount->id,
                'debit_amount' => $sale->paid_amount,
                'credit_amount' => 0,
                'description' => 'Cash received from sale #' . $sale->id,
                'entry_date' => $sale->sale_date
            ];
        }
        
        // If due amount > 0, debit accounts receivable
        if ($sale->due_amount > 0) {
            $entries[] = [
                'account_id' => $receivableAccount->id,
                'debit_amount' => $sale->due_amount,
                'credit_amount' => 0,
                'description' => 'Amount due from sale #' . $sale->id,
                'entry_date' => $sale->sale_date
            ];
        }
        
        // Credit sales
        $entries[] = [
            'account_id' => $salesAccount->id,
            'debit_amount' => 0,
            'credit_amount' => $netAfterDiscount,
            'description' => 'Sales revenue from sale #' . $sale->id,
            'entry_date' => $sale->sale_date
        ];
        
        // Credit VAT payable
        $entries[] = [
            'account_id' => $vatAccount->id,
            'debit_amount' => 0,
            'credit_amount' => $sale->vat_amount,
            'description' => 'VAT collected from sale #' . $sale->id,
            'entry_date' => $sale->sale_date
        ];
        
        // Debit discounts if any
        if ($sale->discount_amount > 0) {
            $entries[] = [
                'account_id' => $discountAccount->id,
                'debit_amount' => $sale->discount_amount,
                'credit_amount' => 0,
                'description' => 'Discount given on sale #' . $sale->id,
                'entry_date' => $sale->sale_date
            ];
        }
        
        // Save all entries
        foreach ($entries as $entry) {
            $accountingEntry = new AccountingEntry($entry);
            $accountingEntry->sale_id = $sale->id;
            $accountingEntry->save();
            
            // Update account balances
            $account = Account::find($entry['account_id']);
            if ($entry['debit_amount'] > 0) {
                $account->balance += $entry['debit_amount'];
            } else {
                $account->balance -= $entry['credit_amount'];
            }
            $account->save();
        }
    }

    public function recordPayment(Sale $sale, array $paymentData)
    {
        $amount = $paymentData['amount'];
        
        // Update sale
        $sale->paid_amount += $amount;
        $sale->due_amount -= $amount;
        
        if ($sale->due_amount == 0) {
            $sale->status = 'paid';
        } else {
            $sale->status = 'partial';
        }
        
        $sale->save();
        
        // Create accounting entry
        $cashAccount = Account::where('name', 'Cash')->first();
        $receivableAccount = Account::where('name', 'Accounts Receivable')->first();
        
        // Debit cash
        AccountingEntry::create([
            'account_id' => $cashAccount->id,
            'debit_amount' => $amount,
            'credit_amount' => 0,
            'description' => 'Payment received for sale #' . $sale->id,
            'entry_date' => $paymentData['payment_date'],
            'sale_id' => $sale->id
        ]);
        
        // Credit accounts receivable
        AccountingEntry::create([
            'account_id' => $receivableAccount->id,
            'debit_amount' => 0,
            'credit_amount' => $amount,
            'description' => 'Payment applied to sale #' . $sale->id,
            'entry_date' => $paymentData['payment_date'],
            'sale_id' => $sale->id
        ]);
        
        // Update account balances
        $cashAccount->balance += $amount;
        $cashAccount->save();
        
        $receivableAccount->balance -= $amount;
        $receivableAccount->save();
    }
}