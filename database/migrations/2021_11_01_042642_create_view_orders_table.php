<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateViewOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement("
        //         CREATE VIEW view_orders AS (
        //                select orders.id,
        //                orders.user_id as user_order_id,
        //                orders.transaction_number,
        //                orders.total_payment,
        //                orders.total_discount,
        //                orders.created_at as order_date,
        //                order_details.invoice_number,
        //                order_details.invoice_total_payment,
        //                order_details.invoice_total_discount,
        //                vendors.name,
        //                vendors.user_id as user_vendor_id,
        //                vendors.image as vendor_image,
        //                orders.status as order_status_id,
        //                master_status_order.description,
        //                order_detail_products.quantity,
        //                order_detail_products.price,
        //                order_detail_products.discount_price,
        //                order_detail_products.fix_price,
        //                order_detail_products.id as product_detail_id,
        //                product_galleries.link
        //         from orders
        //                  left join order_details ON orders.id = order_details.order_id
        //                  left join vendors ON order_details.vendor_id = vendors.id
        //                  left join master_status_order ON orders.status = master_status_order.id
        //                  left join order_detail_products ON order_details.id = order_detail_products.order_detail_id
        //                  left join product_details ON order_detail_products.product_detail_id = product_details.id
        //                  left join product_galleries ON order_detail_products.product_detail_id = product_galleries.product_detail_id
        //         )
        // ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_orders');
    }
}
