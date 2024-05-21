<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 10, 2);
            $table->foreignId('waste_id');
            $table->string('currency', 4)->default('GHS');
            $table->string('unit', 20);
            $table->timestamps();
            $table->softDeletes();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
