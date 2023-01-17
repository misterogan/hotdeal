<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateViewProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
                CREATE VIEW view_products AS (
               select
                    product_details.product_id,
                    product_details.id as product_detail_id,
                    products.name,
                    products.slug,
                    products.sku,
                    products.vendor_id,
                    products.category_id,
                    vendors.name as vendor_name,
                    vendors.province_id as province_id,
                    products.admin_fee,
                    products.status as product_status,
                    products.brand,
                    product_details.price,
                    product_details.variant_key_1,
                    product_details.variant_value_1,
                    product_details.variant_key_2,
                    product_details.variant_value_2,
                    product_details.stock,
                    promotion_discounts.start_date as discount_start,
                    promotion_discounts.end_date  as discount_end,
                    promotion_discounts.status  as promotion_status,
                     CASE  WHEN (NOW() > promotion_discounts.start_date AND promotion_discounts.end_date > NOW()) AND promotion_discounts.status ='active'
                     THEN
                          ((product_details.price + products.admin_fee) * promotion_discount_products.value_discount / 100 )
                     ELSE
                        ((product_details.price + products.admin_fee) * 0 )
                     END as amount_discount,

                     CASE  WHEN (NOW() > promotion_discounts.start_date AND promotion_discounts.end_date > NOW()) AND promotion_discounts.status ='active'
                     THEN
                         (product_details.price + products.admin_fee) - ((product_details.price + products.admin_fee) *  promotion_discount_products.value_discount / 100 )
                     ELSE
                         (product_details.price + products.admin_fee) - ((product_details.price + products.admin_fee) *  0)
                     END as face_price,

                    CASE  WHEN (NOW() > promotion_discounts.start_date AND promotion_discounts.end_date > NOW()) AND promotion_discounts.status ='active'
                     THEN
                        promotion_discount_products.value_discount
                     ELSE
                         0
                     END as value_discount,


                    product_details.status as product_details_status,
                    categories.category,
                    coalesce(promotion_free_shipments.shipping_fee_discount,0) as shipping_fee_discount_value
                    from product_details
                     left join products ON products.id = product_details.product_id
                     left join categories ON categories.id = products.category_id
                     left join promotion_discount_products ON promotion_discount_products.product_id = products.id AND promotion_discount.vendor_id = products.vendor_id
                     left join promotion_discounts ON promotion_discounts.id = promotion_discount_products.promotion_discounts_id
                     left join vendors ON vendors.id = products.vendor_id
                     left join promotion_free_shipments ON promotion_free_shipments.product_id = products.id

                )
        ");

        // View Product Riyanti
//         DB::statement("
//         create view view_products(product_id, product_detail_id, name, slug, sku, vendor_id, category_id, vendor_name,
//                           province_id, admin_fee, product_status, brand, price, variant_key_1, variant_value_1,
//                           variant_key_2, variant_value_2, stock, discount_start, discount_end, promotion_status,
//                           amount_discount, face_price, value_discount, product_details_status, category,
//                           shipping_fee_discount_value) as
// SELECT product_details.product_id,
//        product_details.id                                                     AS product_detail_id,
//        products.name,
//        products.slug,
//        products.sku,
//        products.vendor_id,
//        products.category_id,
//        vendors.name                                                           AS vendor_name,
//        vendors.province_id,
//        products.admin_fee,
//        products.status                                                        AS product_status,
//        products.brand,
//        product_details.price,
//        product_details.variant_key_1,
//        product_details.variant_value_1,
//        product_details.variant_key_2,
//        product_details.variant_value_2,
//        product_details.stock,
//        promotion_discounts.start_date                                         AS discount_start,
//        promotion_discounts.end_date                                           AS discount_end,
//        promotion_discounts.status                                             AS promotion_status,
//        CASE
//            WHEN ((now() > promotion_discounts.start_date) AND (promotion_discounts.end_date > now()) AND
//                  ((promotion_discounts.status)::text = 'active'::text)) THEN (
//                    promotion_discount_products.value_discount)
//            ELSE ((product_details.price + products.admin_fee) * (0)::numeric)
//            END                                                                AS amount_discount,
//        CASE
//            WHEN ((now() > promotion_discounts.start_date) AND (promotion_discounts.end_date > now()) AND
//                  ((promotion_discounts.status)::text = 'active'::text)) THEN (
//                    (product_details.price + products.admin_fee) - promotion_discount_products.value_discount)
//            ELSE ((product_details.price + products.admin_fee) -
//                  ((product_details.price + products.admin_fee) * (0)::numeric))
//            END                                                                AS face_price,
//        CASE
//            WHEN ((now() > promotion_discounts.start_date) AND (promotion_discounts.end_date > now()) AND
//                  ((promotion_discounts.status)::text = 'active'::text)) THEN
//                ceiling(((product_details.price + products.admin_fee) - ((product_details.price + products.admin_fee) - promotion_discount_products.value_discount)) /
//                (product_details.price + products.admin_fee) * 100)
//            ELSE (0)::numeric
//            END                                                                AS value_discount,
//        product_details.status                                                 AS product_details_status,
//        categories.category,
//        COALESCE(promotion_free_shipments.shipping_fee_discount, (0)::numeric) AS shipping_fee_discount_value
// FROM ((((((product_details
//     LEFT JOIN products ON ((products.id = product_details.product_id)))
//     LEFT JOIN categories ON ((categories.id = products.category_id)))
//     LEFT JOIN promotion_discount_products ON ((promotion_discount_products.product_id = products.id)))
//     LEFT JOIN promotion_discounts ON ((promotion_discounts.id = promotion_discount_products.promotion_discounts_id)))
//     LEFT JOIN vendors ON ((vendors.id = products.vendor_id)))
        //  LEFT JOIN promotion_free_shipments ON ((promotion_free_shipments.product_id = products.id)));
        // ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS view_products');
    }
}
