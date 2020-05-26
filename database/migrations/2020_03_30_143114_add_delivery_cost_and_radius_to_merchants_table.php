<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryCostAndRadiusToMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('merchants')) {
            Schema::table('merchants', function (Blueprint $table) {
                if (!Schema::hasColumn('merchants', 'delivery_cost')) {
                    $table->integer('delivery_cost')->default(0)->after('allow_collection');
                }
                if (!Schema::hasColumn('merchants', 'delivery_radius')) {
                    $table->integer('delivery_radius')->default(0)->after('delivery_cost');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('merchants')) {
            Schema::table('merchants', function (Blueprint $table) {
                if (Schema::hasColumn('merchants', 'delivery_cost')) {
                    $table->dropColumn('delivery_cost');
                }
                if (Schema::hasColumn('merchants', 'delivery_radius')) {
                    $table->dropColumn('delivery_radius');
                }
            });
        }
    }
}
