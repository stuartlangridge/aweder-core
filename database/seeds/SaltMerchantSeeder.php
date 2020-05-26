<?php

use App\Merchant;
use Illuminate\Database\Seeder;

class SaltMerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchants = Merchant::whereNull('salt')->get();

        if ($merchants->isNotEmpty()) {
            $merchants->each(function ($merchant) {
                $merchant->salt = Str::random(10);
                $merchant->save();
            });
        }
    }
}
