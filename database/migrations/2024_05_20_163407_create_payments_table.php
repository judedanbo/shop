<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->string('payment_method', 100);
            $table->string('phone', 15);
            $table->string('transactionId', 15);
            $table->string('comments')->nullable();
            $table->foreignId('order_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
