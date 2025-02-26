<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBuyerIdToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // buyer_id カラムを追加（unsignedBigInteger, NULL許容）
            $table->unsignedBigInteger('buyer_id')->nullable()->after('categories');
            
            // 外部キー制約を設定する場合（任意）
            // $table->foreign('buyer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // 外部キー制約を設定している場合は、先に削除
            // $table->dropForeign(['buyer_id']);
            $table->dropColumn('buyer_id');
        });
    }
}
