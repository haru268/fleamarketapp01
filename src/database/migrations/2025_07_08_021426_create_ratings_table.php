<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('ratings', function (Blueprint $table) {
        $table->id();

      
        $table->foreignId('trade_id')->constrained()->cascadeOnDelete();
        $table->foreignId('rater_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('ratee_id')->constrained('users')->cascadeOnDelete();

      
        $table->unsignedTinyInteger('score');

       
        $table->string('comment', 255)->nullable();

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('ratings');
}

}
