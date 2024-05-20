<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('other_names', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('phone', 15);
            $table->timestamps();
            $table->softDeletes();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
