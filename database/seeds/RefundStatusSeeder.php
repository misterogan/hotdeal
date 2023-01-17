<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\RefundStatus;
use Carbon\Carbon;

class RefundStatusSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        RefundStatus::truncate();
        $now = Carbon::now();

        $refund_status = array(
            array('id' => 1,'description' => 'menunggu konfirmasi vendor' ,'description_vendor' => 'Menunggu konfirmasi vendor','status' => 'waiting','created_at' => $now, 'updated_at' => $now),
            array('id' => 2,'description' => 'vendor mengkonfirmasi refund,  customer akan menyiapkan barang untuk dikirim' ,'description_vendor' => 'vendor mengkonfirmasi refund,  customer akan menyiapkan barang untuk dikirim','status' => 'confirm','created_at' => $now, 'updated_at' => $now),
            array('id' => 3,'description' => 'barang diterima vendor' ,'description_vendor' => 'barang diterima vendor','status' => 'delivered','created_at' => $now, 'updated_at' => $now),
            array('id' => 4,'description' => 'refund diterima vendor, menunggu validasi admin hotdeal' ,'description_vendor' => 'refund diterima vendor, menunggu validasi admin hotdeal','status' => 'processed','created_at' => $now, 'updated_at' => $now),
            array('id' => 5,'description' => 'admin menerima refund, proses pengembalian dana' ,'description_vendor' => 'admin menerima refund, proses pengembalian dana','status' => 'approved','created_at' => $now, 'updated_at' => $now),
            array('id' => 6,'description' => 'pengembalian dana sukses' ,'description_vendor' => 'pengembalian dana sukses','status' => 'success','created_at' => $now, 'updated_at' => $now),
            array('id' => 7,'description' => 'refund direject oleh admin' ,'description_vendor' => 'refund direject oleh admin','status' => 'reject_admin','created_at' => $now, 'updated_at' => $now),
            array('id' => 8,'description' => 'refund direject oleh vendor' ,'description_vendor' => 'refund direject oleh vendor','status' => 'reject_vendor','created_at' => $now, 'updated_at' => $now),
        );

        DB::table('refund_status')->insert($refund_status);
    }
}
