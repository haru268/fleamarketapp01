<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();

          
            $table->foreignId('product_id')
                  ->constrained()       
                  ->cascadeOnDelete();

         
            $table->foreignId('seller_id')
                  ->constrained('users')    
                  ->cascadeOnDelete();

            $table->foreignId('buyer_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

          
            $table->enum('status', ['progress', 'done'])
                  ->default('progress');

            $table->timestamps();             
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trades');
    }
}

