<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $service = new Service();
        $service->name = "Add product";
        $service->save();

        $service = new Service();
        $service->name = "Launch marketing campaing";
        $service->save();
    }
}
