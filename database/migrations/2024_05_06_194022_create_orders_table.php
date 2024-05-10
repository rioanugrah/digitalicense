<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('order_code');
            $table->string('order_reference');
            $table->text('billing_order');
            $table->string('price');
            $table->string('status');
            // $table->integer('user_id');
            $table->uuid('user_generate')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('order_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('orders_id');
            $table->string('order_name');
            $table->string('qty');
            $table->string('price');
            $table->text('product_license')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_detail');
    }
}
