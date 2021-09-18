<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
/*use PaymentSeeder;
use PaymentStatusSeeder;
use PaymenteTypeSeeder;
use RolSeeder;
use ServiceSeeder;
use WalletServiceSeeder;
use UserSeeder;
*/
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolSeeder::class,
            UserSeeder::class,
            PaymentStatusSeeder::class,
            PaymentTypeSeeder::class,
            ServiceSeeder::class,
            WalletSeeder::class,//Se liga a los usuarios
            PaymentSeeder::class
        ]);
        // \App\Models\User::factory(10)->create();

    }
}
