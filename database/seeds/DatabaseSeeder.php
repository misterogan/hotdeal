<?php

use Illuminate\Database\Seeder;
use AzisHapidin\IndoRegion\RawDataGetter;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminTableSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(\Database\Seeders\IndoRegionDistrictSeeder::class);
        $this->call(\Database\Seeders\IndoRegionProvinceSeeder::class);
        $this->call(\Database\Seeders\IndoRegionRegencySeeder::class);
        $this->call(\Database\Seeders\IndoRegionSeeder::class);
        $this->call(\Database\Seeders\IndoRegionVillageSeeder::class);
        $this->call(PaymentMethodMitraSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(RefundStatusSeeder::class);
        $this->call(RejekiNomplokBannerSeeder::class);
    }
}
