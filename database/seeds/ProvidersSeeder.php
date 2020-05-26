<?php

use App\Provider;
use Illuminate\Database\Seeder;

class ProvidersSeeder extends Seeder
{
    protected Provider $model;

    public function __construct(Provider $providerModel)
    {
        $this->model = $providerModel;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->model->firstOrCreate(['name' => 'Stripe']);
    }
}
