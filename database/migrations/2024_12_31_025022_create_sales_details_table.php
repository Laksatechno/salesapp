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
        Schema::create('sales_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            // $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null'); // Gunakan set null
            $table->integer('quantity');
            $table->bigInteger('price');
            $table->bigInteger('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_details');
    }
};
