<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->string('origin')->nullable();
            $table->string('source')->nullable();
            $table->foreignId('order_id');
            $table->foreignId('waste_id');
            $table->foreignId('price_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};