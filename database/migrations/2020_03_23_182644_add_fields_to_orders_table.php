<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'total_cost')) {
                    $table->integer('total_cost')->nullable()->after('rejection_reason');
                }

                if (!Schema::hasColumn('orders', 'final_cost')) {
                    $table->integer('final_cost')->nullable()->after('total_cost');
                }

                if (!Schema::hasColumn('orders', 'merchant_note')) {
                    $table->longText('merchant_note')->nullable()->after('final_cost');
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
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'total_cost')) {
                    $table->dropColumn('total_cost');
                }

                if (Schema::hasColumn('orders', 'final_cost')) {
                    $table->dropColumn('final_cost');
                }

                if (Schema::hasColumn('orders', 'merchant_note')) {
                    $table->dropColumn('merchant_note');
                }
            });
        }
    }
}
