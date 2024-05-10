<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug');
            $table->integer('category_id');
            $table->string('name');
            $table->text('description');
            $table->string('price');
            $table->string('qty',10);
            $table->text('image');
            $table->text('link_file')->nullable();
            $table->text('keywords');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('product_detail', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
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
        Schema::dropIfExists('product');
        Schema::dropIfExists('product_detail');
    }
}
