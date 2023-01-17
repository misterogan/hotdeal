<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\RejekiNomplokBanner;
use Carbon\Carbon;

class RejekiNomplokBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RejekiNomplokBanner::truncate();
        $now = Carbon::now();

        $banner = array(
            array('id' => 1,'banner' => 'https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/upload/rejeki-nomplok/61d65e4e9cb38-rejeki-nomplok-banner1641438798.png' ,'created_by' => 'seed','updated_by' => 'seed','created_at' => $now, 'updated_at' => $now),
        );

        DB::table('rejeki_nomplok_banners')->insert($banner);
    }
}
