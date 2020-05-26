<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class UpdateUsersTable
 */
class UpdateUsersTable extends Migration
{
    private const TABLE_NAME = 'users';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable(self::TABLE_NAME)) {
            Schema::table(self::TABLE_NAME, function (Blueprint $table) {
                if (Schema::hasColumn(self::TABLE_NAME, 'name')) {
                    $table->removeColumn('name');
                }
            });
        } else {
            Log::error('the table ' . self::TABLE_NAME . ' does not exist');
        }


        if (!Schema::hasTable('merchant_users')) {
            Schema::create('merchant_users', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->foreignId('merchant_id')
                    ->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            });
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
                if (!Schema::hasColumn(self::TABLE_NAME, 'name')) {
                    $table->string('name');
                }
            });
        } else {
            Log::error('the table ' . self::TABLE_NAME . ' does not exist');
        }

        if (Schema::hasTable('merchant_users')) {
            Schema::dropIfExists('merchant_users');
        }
    }
}
