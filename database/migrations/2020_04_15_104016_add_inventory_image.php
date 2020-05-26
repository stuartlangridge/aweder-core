<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInventoryImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('inventories')) {
            Schema::table('inventories', function (Blueprint $table) {
                if (!Schema::hasColumn('inventories', 'image')) {
                    $table->text('image')->nullable()->after('price');
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
        if (Schema::hasTable('inventories')) {
            Schema::hasTable('inventories', function (Blueprint $table) {
                if (Schema::hasColumn('inventories', 'image')) {
                    $table->dropColumn('image');
                }
            });
        }
    }
}
