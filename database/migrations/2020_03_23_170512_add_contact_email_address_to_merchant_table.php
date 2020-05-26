<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactEmailAddressToMerchantTable extends Migration
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
                if (!Schema::hasColumn('merchants', 'contact_email')) {
                    $table->string('contact_email')->nullable()->after('name');
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
                if (Schema::hasColumn('merchants', 'contact_email')) {
                    $table->dropColumn('contact_email');
                }
            });
        }
    }
}
