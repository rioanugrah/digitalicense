<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderLicenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_license', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->uuid('order_id')->nullable();
            $table->uuid('product_id')->nullable();
            $table->text('product_license')->nullable();
            $table->string('status')->nullable();
            $table->uuid('user_generate')->nullable();
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
        Schema::dropIfExists('order_license');
    }
}
