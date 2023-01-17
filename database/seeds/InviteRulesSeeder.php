<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InviteRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $default_data = [
            [
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonth(3),
                'count_from' => 0,
                'count_after' => 10,
                'status' => 'active',
                'percentage' => 5,
                'created_at' => Carbon::now()
            ],
            [
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonth(3),
                'count_from' => 11,
                'count_after' => 30,
                'status' => 'active',
                'percentage' => 10,
                'created_at' => Carbon::now()
            ],
            [
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonth(3),
                'count_from' => 31,
                'count_after' => 100000,
                'status' => 'active',
                'percentage' => 15,
                'created_at' => Carbon::now()
            ],
        ];
        DB::table('invite_rules')->insert($default_data);
    }
}
