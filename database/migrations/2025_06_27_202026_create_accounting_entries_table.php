<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounting_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained();
            $table->decimal('debit_amount', 10, 2)->default(0);
            $table->decimal('credit_amount', 10, 2)->default(0);
            $table->string('description');
            $table->date('entry_date');
            $table->foreignId('sale_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_entries');
    }
};
