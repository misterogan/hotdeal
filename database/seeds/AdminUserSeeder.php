<?php

use Illuminate\Database\Seeder;
use App\Admin;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'oganadmin',
            'email' => 'ogan@cashtree.id',
            'password' => bcrypt('12345678'),
        ]);
    }
}
