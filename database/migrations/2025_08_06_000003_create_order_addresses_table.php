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
        Schema::create('order_addresses', function (Blueprint $table) {
            $table->id();

            // Foreign key to orders table
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            // Shipping and delivery addresses
            $table->string('shipping_name')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_postcode')->nullable();
            $table->string('shipping_country')->nullable();
            $table->boolean('same_as_shipping_address')->nullable()->default(false);
            $table->string('delivery_name')->nullable();
            $table->string('delivery_phone')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('delivery_city')->nullable();
            $table->string('delivery_state')->nullable();
            $table->string('delivery_postcode')->nullable();
            $table->string('delivery_country')->nullable();

            // Soft deletes and timestamps
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};
