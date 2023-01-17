<?php

use App\Privacy;
use Illuminate\Database\Seeder;

class PrivacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Privacy::create([
            'title' => 'Privacy Policy',
            'decription' => 'deskripsi',
            'slug' => 'privacy-policy',
            'status' => 'active'
        ]);

        Privacy::create([
            'title' => 'Refund Policy',
            'decription' => 'deskripsi',
            'slug' => 'refund-policy',
            'status' => 'active'
        ]);

        Privacy::create([
            'title' => 'Shipping Policy',
            'decription' => 'deskripsi',
            'slug' => 'shipping-policy',
            'status' => 'active'
        ]);

        Privacy::create([
            'title' => 'Terms of Services',
            'decription' => 'deskripsi',
            'slug' => 'terms-of-service',
            'status' => 'active'
        ]);
    }
}
