<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('product_refunds', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 20);
            $table->unsignedBigInteger('order_id'); // order table id *primary key*
            $table->unsignedBigInteger('order_product_id')->unique(); // order_product table id *primary key*
            $table->decimal('price')->default(0);
            $table->integer('point')->nullable();
            $table->integer('ref_user_point')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ref_user_id')->nullable();
            $table->unsignedInteger('status')->default(0);
            $table->string('note')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('order_product_id')->references('id')->on('order_products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ref_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_refunds');
    }
};