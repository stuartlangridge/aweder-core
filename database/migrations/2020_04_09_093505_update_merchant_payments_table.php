<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMerchantPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('merchant_payment')) {
            Schema::table('merchant_payment', function (Blueprint $table) {
                if (Schema::hasColumn('merchant_payment', 'api_key')) {
                    $table->dropColumn('api_key');
                }
                if (Schema::hasColumn('merchant_payment', 'type')) {
                    $table->dropColumn('type');
                }
                if (!Schema::hasColumn('merchant_payment', 'data')) {
                    $table->json('data')->after('id');
                }
                if (!Schema::hasColumn('merchant_payment', 'merchant_id')) {
                    $table->foreignId('merchant_id')
                        ->after('data')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
                }
                if (!Schema::hasColumn('merchant_payment', 'provider_id')) {
                    $table->foreignId('provider_id')
                        ->after('merchant_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
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
        if (Schema::hasTable('merchant_payment')) {
            Schema::table('merchant_payment', function (Blueprint $table) {
                if (Schema::hasColumn('merchant_payment', 'data')) {
                    $table->dropColumn('data');
                }
                if (Schema::hasColumn('merchant_payment', 'merchant_id')) {
                    $table->dropForeign(['merchant_id']);
                }
                if (Schema::hasColumn('merchant_payment', 'provider_id')) {
                    $table->dropForeign(['provider_id']);
                }
            });
        }
    }
}
