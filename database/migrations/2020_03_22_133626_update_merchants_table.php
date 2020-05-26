<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class UpdateMerchantsTable
 */
class UpdateMerchantsTable extends Migration
{
    private const TABLE_NAME = 'merchants';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable(self::TABLE_NAME)) {
            Schema::table(self::TABLE_NAME, function (Blueprint $table) {
                $table->string('mobile_number')
                    ->nullable()
                    ->comment('the mobile number for SMS notifications')
                    ->after('address');
                $table->string('notification_method')
                    ->nullable()
                    ->comment('the method to use for notifications')
                    ->after('mobile_number');
                $table->string('customer_phone_number')
                    ->nullable()
                    ->comment('the telephone number to display to customers')
                    ->after('notification_method');
                $table->boolean('allow_delivery')
                    ->nullable()
                    ->comment('whether the merchant allows delivery')
                    ->after('customer_phone_number');
                $table->boolean('allow_collection')
                    ->nullable()
                    ->comment('whether the merchant allows collection')
                    ->after('allow_delivery');
                $table->text('address_name_number')
                    ->nullable()
                    ->comment('the building name or number for the address')
                    ->after('allow_collection');
                $table->text('address_street')
                    ->nullable()
                    ->comment('the street for the address')
                    ->after('address_name_number');
                $table->text('address_locality')
                    ->nullable()
                    ->comment('the locality for the address')
                    ->after('address_street');
                $table->text('address_city')
                    ->nullable()
                    ->comment('the city for the address')
                    ->after('address_locality');
                $table->text('address_county')
                    ->nullable()
                    ->comment('the county for the address')
                    ->after('address_city');
                $table->text('address_postcode')
                    ->nullable()
                    ->comment('the postcode for the address')
                    ->after('address_county');
            });


        } else {
            Log::error('the table ' . self::TABLE_NAME . ' does not exist');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable(self::TABLE_NAME)) {
            Schema::table(self::TABLE_NAME, function (Blueprint $table) {
                $table->removeColumn('mobile_number');
                $table->removeColumn('notification_method');
                $table->removeColumn('customer_phone_number');
                $table->removeColumn('allow_delivery');
                $table->removeColumn('allow_collection');
                $table->removeColumn('address_name_number');
                $table->removeColumn('address_street');
                $table->removeColumn('address_locality');
                $table->removeColumn('address_city');
                $table->removeColumn('address_county');
                $table->removeColumn('address_postcode');

            });
        } else {
            Log::error('the table ' . self::TABLE_NAME . ' does not exist');
        }
    }
}
