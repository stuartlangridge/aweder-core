<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateMerchantPaymentsTable
 */
class CreateMerchantPaymentsTable extends Migration
{
    private const TABLE_NAME = 'merchant_payment';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable(self::TABLE_NAME) === false) {
            Schema::create(self::TABLE_NAME, function (Blueprint $table) {
                $table->id();
                $table->text('api_key')->comment('the providers api key');
                $table->string('type')->comment('the type of payment provider');
                $table->timestamps();
            });
        } else {
            Log::error('the table ' . self::TABLE_NAME . ' already exists');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable(self::TABLE_NAME)) {
            Schema::drop(self::TABLE_NAME);
        } else {
            Log::error('table ' . self::TABLE_NAME . ' does not exist');
        }
    }
}
