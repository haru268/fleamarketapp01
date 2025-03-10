<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTableFinal extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('price')->nullable();
            $table->boolean('is_sold')->default(false);
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->timestamp('purchased_at')->nullable();
            
            $table->string('category')->nullable(); 
            $table->string('categories')->nullable();
            
            $table->string('condition')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_recommended')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
