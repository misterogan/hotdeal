<?php

use App\Helpers\Orders;
use App\Jobs\ProcessExpiredOrder;
use Illuminate\Support\Facades\Route;
use App\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/admin/phpinfo', function() {
   phpinfo();
});
Route::get('/admin/login', 'Admin\AuthController@login')->name('admin.login');
Route::get('/admin/login-verification', 'Admin\AuthController@verification')->name('verification');
Route::get('/admin/verification', 'Admin\AuthController@check_otp_view')->name('check_otp_view');

Route::get('/sitemap.xml', 'SiteMapController@index');

Route::get('/cookie/set','CookieController@setCookie');
Route::get('/cookie/get','CookieController@getCookie');

Route::get('/email/user', function (){
    //phpinfo();
     $data = ['name' => "Ridcat Simbolon", 'content' => 'asdasdas'];
     return view('emails.email_blast' , $data);
});

// Route::get('/admin/job/test', function(){
//     dispatch(new App\Jobs\TestQue());
//     dd('done bro!');
// });

// Route::get('/admin/test', 'Admin\AdminController@test');
// Route::get('/admin/job/test', 'Admin\AdminController@job_test');

Route::get('/test/response', 'HomeController@test_response')->name('test');

Route::post('/admin/login', 'Admin\AuthController@check_login');
Route::post('/admin/check-verification', 'Admin\AuthController@check_verification');
Route::post('/admin/check-otp', 'Admin\AuthController@check_otp');

Route::middleware('auth:admin')->group(function(){
    // EMAIL TEST

    Route::get('/admin/email/transaction', function (){
        //phpinfo();
        $data = ['name' => "Ridcat Simbolon", 'content' => 'asdasdas'];
         return view('emails.user_transaction' , $data);
    });
    //Route::get('/admin/email/transaction', 'Admin\AdminController@email_transaction');
    //General
    Route::get('/admin/dashboard', 'Admin\AdminController@index');
    Route::get('/user/select2', 'Admin\AdminController@user_select2');
    Route::get('/city/select2', 'Admin\AdminController@city_select2');
    Route::get('/suburb/select2', 'Admin\AdminController@suburb_select2');
    Route::get('/area/select2', 'Admin\AdminController@area_select2');
    Route::get('/admin/logout', 'Admin\AuthController@admin_logout');
    Route::get('/admin/logout', 'Admin\AuthController@admin_logout');
    Route::get('/admin/email', 'Admin\AdminController@send_email');
    //About
    Route::get('/admin/about', 'Admin\AboutUsController@index');
    Route::post('/admin/about/edit', 'Admin\AboutUsController@edit');
    //Highlight Title
    Route::get('/admin/highlight/title', 'Admin\HighlightController@set_title_view');
    Route::post('/admin/highlight/set-title', 'Admin\HighlightController@set_title');
    //Administrator
    Route::get('/admin/administrator', 'Admin\AdministratorController@index');
    Route::get('/administrator/dt', 'Admin\AdministratorController@administrator_dt');
    Route::get('/admin/administrator/create', 'Admin\AdministratorController@create_view');
    Route::post('/admin/administrator/create', 'Admin\AdministratorController@submit');
    Route::get('/admin/administrator/edit/{id}', 'Admin\AdministratorController@edit_view');
    Route::post('/admin/administrator/edit', 'Admin\AdministratorController@edit');
    //Banner
    Route::get('/admin/banner', 'Admin\BannerController@index');
    Route::get('/banner/dt', 'Admin\BannerController@banner_dt');
    Route::get('/admin/banner/create', 'Admin\BannerController@create_view');
    Route::post('/admin/banner/create', 'Admin\BannerController@submit');
    Route::post('/admin/banner/delete-image', 'Admin\BannerController@delete_image');
    Route::get('/admin/banner/edit/{id}', 'Admin\BannerController@edit_view');
    Route::post('/admin/banner/edit', 'Admin\BannerController@edit');
    Route::get('/banner/dt', 'Admin\BannerController@banner_dt');
    Route::get('/banner/filter/dt', 'Admin\BannerController@banner_filter_dt');
    Route::get('/banner/product/detail/edit/dt', 'Admin\BannerController@banner_detail_dt');
    Route::get('/banner/search', 'Admin\BannerController@get_search');
    Route::get('/admin/banner/login/popup', 'Admin\BannerController@popup_view');
    Route::post('/admin/banner/login/popup/update', 'Admin\BannerController@popup_update');
    //Discount
    Route::get('/admin/discount', 'Admin\DiscountController@index');
    Route::get('/discount/dt', 'Admin\DiscountController@discount_dt');
    Route::get('/admin/discount/create', 'Admin\DiscountController@create_view');
    Route::post('/admin/discount/create', 'Admin\DiscountController@submit');
    Route::get('/admin/discount/edit/{id}', 'Admin\DiscountController@edit_view');
    Route::post('/admin/discount/edit', 'Admin\DiscountController@edit');
    Route::get('/discount/dt', 'Admin\DiscountController@discount_dt');
    Route::get('/discount/set/dt', 'Admin\DiscountController@set_discount_dt');
    Route::post('/discount/set', 'Admin\DiscountController@set_discount');
    //FAQs
    Route::get('/admin/faq', 'Admin\FAQsController@index');
    Route::get('/faq/dt', 'Admin\FAQsController@faq_dt');
    Route::get('/admin/faq/create', 'Admin\FAQsController@create_view');
    Route::post('/admin/faq/create', 'Admin\FAQsController@submit');
    Route::get('/admin/faq/edit/{id}', 'Admin\FAQsController@edit_view');
    Route::post('/admin/faq/edit', 'Admin\FAQsController@edit');
    //Page
    Route::get('/admin/page', 'Admin\PageController@index');
    Route::get('/page/dt', 'Admin\PageController@page_dt');
    Route::get('/admin/page/create', 'Admin\PageController@create_view');
    Route::post('/admin/page/create', 'Admin\PageController@submit');
    Route::get('/admin/page/edit/{id}', 'Admin\PageController@edit_view');
    Route::post('/admin/page/edit', 'Admin\PageController@edit');
    //Privacy Policy
    Route::get('admin/privacy-policy', 'Admin\PrivacyPolicyController@index');
    Route::get('/privacy/dt', 'Admin\PrivacyPolicyController@privacy_dt');
    Route::get('/admin/privacy/create', 'Admin\PrivacyPolicyController@create_view');
    Route::post('/admin/privacy/create', 'Admin\PrivacyPolicyController@submit');
    Route::get('/admin/privacy/edit/{id}', 'Admin\PrivacyPolicyController@edit_view');
    Route::post('/admin/privacy/edit', 'Admin\PrivacyPolicyController@edit');
    //Flash Sale
    Route::get('/admin/flashsale', 'Admin\FlashsaleController@index');
    Route::get('/flashsale/dt', 'Admin\FlashsaleController@flashsale_dt');
    Route::get('/admin/flashsale/create', 'Admin\FlashsaleController@create_view');
    Route::post('/admin/flashsale/create', 'Admin\FlashsaleController@submit');
    Route::post('/admin/flashsale/delete-image', 'Admin\FlashsaleController@delete_image');
    Route::get('/admin/flashsale/edit/{id}', 'Admin\FlashsaleController@edit_view');
    Route::post('/admin/flashsale/edit', 'Admin\FlashsaleController@edit');
    Route::get('/admin/flashsale/log/{id}', 'Admin\FlashsaleController@log_view');

    //Highlight Product
    Route::get('/admin/highlight', 'Admin\HighlightController@index');
    Route::get('/highlight/dt', 'Admin\HighlightController@highlight_dt');
    Route::get('/admin/highlight/create', 'Admin\HighlightController@create_view');
    Route::post('/admin/highlight/create', 'Admin\HighlightController@submit');
    Route::get('/admin/highlight/edit/{id}', 'Admin\HighlightController@edit_view');
    Route::post('/admin/highlight/edit', 'Admin\HighlightController@edit');
    //Product Controls
    Route::get('/admin/product', 'Admin\ProductController@index');
    Route::get('/product/dt', 'Admin\ProductController@product_dt');
    Route::get('/admin/product/create', 'Admin\ProductController@create_view');
    Route::post('/admin/product/create', 'Admin\ProductController@submit');
    Route::post('/admin/product/image/variant', 'Admin\ProductController@variant_image');
    Route::get('/admin/product/edit/{id}', 'Admin\ProductController@edit_view');
    Route::post('/admin/product/edit', 'Admin\ProductController@edit');
    Route::post('/admin/product/delete-image', 'Admin\ProductController@delete_image');
    //mass product
    Route::get('/admin/product/mass/create', 'Admin\ProductController@mass_create_view');
    Route::get('/admin/product/mass/download', 'Admin\ProductController@download');
    Route::post('/admin/product/mass/upload', 'Admin\ProductController@import_excel');
    //log product
    Route::get('/admin/product/changes/{id}', 'Admin\ProductController@changes_view');
    Route::get('/product/log/dt', 'Admin\ProductController@log_product_dt');
    //Promotion
    Route::get('/admin/promotion', 'Admin\PromotionController@index');
    Route::get('/promotion/dt', 'Admin\PromotionController@promotion_dt');
    Route::get('/admin/promotion/create', 'Admin\PromotionController@create_view');
    Route::post('/admin/promotion/create', 'Admin\PromotionController@submit');
    Route::post('/admin/promotion/delete-image', 'Admin\PromotionController@delete_image');
    Route::post('/admin/promotion/delete-banner', 'Admin\PromotionController@delete_banner');
    Route::get('/admin/promotion/edit/{code}', 'Admin\PromotionController@edit_view');
    Route::post('/admin/promotion/edit', 'Admin\PromotionController@edit');
    Route::get('/admin/promotion/banner', 'Admin\PromotionController@banner_view');
    Route::post('/admin/promotion/banner', 'Admin\PromotionController@master_banner');
    //Category
    Route::get('/admin/category', 'Admin\CategoryController@index');
    Route::get('/admin/category/create', 'Admin\CategoryController@create_view');
    Route::get('/admin/category/edit/{id}', 'Admin\CategoryController@edit_view');
    Route::post('/admin/category/submit', 'Admin\CategoryController@submit')->name('category.submit');
    Route::post('/admin/category/update', 'Admin\CategoryController@update')->name('category.update');
    Route::get('/category/dt', 'Admin\CategoryController@category_dt');
    //Message
    Route::post('/admin/category/delete', 'Admin\CategoryController@delete');
    Route::get('/admin/message', 'Admin\MessageController@index');
    Route::get('/message/dt', 'Admin\MessageController@message_dt');
    Route::get('/admin/message/create', 'Admin\MessageController@create_view');
    Route::post('/admin/message/submit', 'Admin\MessageController@create')->name('message.submit');
    //User
    Route::get('/admin/user', 'Admin\UserController@index');
    Route::get('/user/dt', 'Admin\UserController@user_dt');
    Route::get('/admin/user/edit/{id}', 'Admin\UserController@edit_view');
    Route::post('/admin/user/edit', 'Admin\UserController@edit');

    // User Searches Keyword
    Route::get('/admin/user/searches', 'Admin\UserController@searches_index');
    Route::get('/admin/user/searches/dt', 'Admin\UserController@searches_dt');

    //Refunds
    Route::get('/admin/refunds', 'Admin\RefundController@index');
    Route::get('/admin/refunds/edit/{id}', 'Admin\RefundController@edit_view');
    Route::get('/refund/dt', 'Admin\RefundController@refund_dt');
    Route::post('/admin/refunds/approve', 'Admin\RefundController@approve');
    Route::post('/admin/refunds/status/update', 'Admin\RefundController@update_status');

    //Strengths
    Route::get('/admin/strengths', 'Admin\StrengthController@index');
    Route::get('/strengths/dt', 'Admin\StrengthController@strength_dt');
    Route::get('/admin/strengths/edit/{id}', 'Admin\StrengthController@edit_view');
    Route::post('/admin/strengths/edit', 'Admin\StrengthController@edit');

    // File Upload
    Route::get('admin/file', 'Admin\FileUploadController@upload_index');
    Route::get('admin/file/dt', 'Admin\FileUploadController@file_dt');
    Route::get('admin/file/create', 'Admin\FileUploadController@create_view');
    Route::post('admin/file/submit', 'Admin\FileUploadController@submit');
    //Transaction
    Route::get('/admin/transaction', 'Admin\TransactionController@index');
    Route::get('/transaction/dt', 'Admin\TransactionController@transaction_dt');
    Route::get('/admin/transaction/edit/{transaction_number}', 'Admin\TransactionController@edit_view');
    Route::post('/admin/transaction/edit', 'Admin\TransactionController@edit');
    Route::post('/transaction/status/update', 'Admin\TransactionController@change_status');
    //Order
    Route::get('/admin/order', 'Admin\OrderController@index');
    Route::get('/order/dt', 'Admin\OrderController@order_dt');
    Route::get('/admin/order/edit/{slugged_invoice_number}', 'Admin\OrderController@edit_view');
    Route::post('/admin/order/edit', 'Admin\OrderController@edit');
    Route::get('admin/order-log/dt', 'Admin\OrderController@log_dt');
    // POINT
    Route::get('/admin/hotpoint/send/point', 'Admin\HotpointController@point');
    Route::post('/admin/hotpoint/send/otp/point', 'Admin\HotpointController@send_otp_point');
    Route::post('/admin/hotpoint/send/point', 'Admin\HotpointController@send_point');
    Route::get('/admin/hotpoint/send/point/cancel/order', 'Admin\HotpointController@send_point_from_cancel_order');
    Route::get('/admin/hotpoint/send/event/point', 'Admin\HotpointController@send_point_event');
    Route::get('/admin/hotpoint/code', 'Admin\HotpointController@code_view');
    Route::get('/admin/hotpoint/code/create', 'Admin\HotpointController@code_create');
    Route::post('/admin/hotpoint/code/create', 'Admin\HotpointController@code_submit');
    Route::get('/admin/hotpoint/code/dt', 'Admin\HotpointController@hotpoint_dt');
    Route::get('/admin/hotpoint/code/{id}', 'Admin\HotpointController@edit_view');
    Route::post('/admin/hotpoint/code/update', 'Admin\HotpointController@update');

    Route::get('/admin/hotpoint/send', 'Admin\HotpointController@index');
    Route::get('/admin/hotpoint/dt', 'Admin\HotpointController@get_data_send');

    //Vendor
    Route::get('/admin/vendor', 'Admin\VendorController@index');
    Route::get('/vendor/dt', 'Admin\VendorController@vendor_dt');
    Route::get('/admin/vendor/create', 'Admin\VendorController@create_view');
    Route::post('/admin/vendor/create', 'Admin\VendorController@submit');
    Route::get('/admin/vendor/edit/{id}', 'Admin\VendorController@edit_view');
    Route::post('/admin/vendor/edit', 'Admin\VendorController@edit');
    // Rejeki Nomplok
    Route::get('admin/rejeki-nomplok/master', 'Admin\RejekiNomplokController@master_view');
    Route::get('admin/rejeki-nomplok/banner', 'Admin\RejekiNomplokController@banner_view');
    Route::post('admin/rejeki-nomplok/delete-banner', 'Admin\RejekiNomplokController@delete_image');
    Route::get('admin/rejeki-nomplok/products', 'Admin\RejekiNomplokController@products_view');
    Route::get('admin/rejeki-nomplok/winners', 'Admin\RejekiNomplokController@winners_view');
    Route::get('admin/rejeki-nomplok/coupons', 'Admin\RejekiNomplokController@coupons_view');
    Route::get('admin/rejeki-nomplok/coupon/{id}', 'Admin\RejekiNomplokController@coupons_detail_view');
    Route::get('admin/rejeki-nomplok/running-text', 'Admin\RejekiNomplokController@running_text');
    Route::post('admin/rejeki-nomplok/running-text/edit', 'Admin\RejekiNomplokController@save_running_text');
    Route::get('/rejeki-nomplok/master/dt', 'Admin\RejekiNomplokController@master_dt');
    Route::get('/rejeki-nomplok/products/dt', 'Admin\RejekiNomplokController@products_dt');
    Route::get('/rejeki-nomplok/winners/dt', 'Admin\RejekiNomplokController@winners_dt');
    Route::get('/rejeki-nomplok/coupons/dt', 'Admin\RejekiNomplokController@coupons_dt');
    Route::get('/rejeki-nomplok/couponDetails/dt', 'Admin\RejekiNomplokController@couponDetails_dt');
    Route::get('admin/rejeki-nomplok/products/create', 'Admin\RejekiNomplokController@products_create_view');
    Route::get('admin/rejeki-nomplok/products/edit/{id}', 'Admin\RejekiNomplokController@products_edit_view');
    Route::post('admin/rejeki-nomplok/products/submit', 'Admin\RejekiNomplokController@products_submit');
    Route::post('admin/rejeki-nomplok/products/edit', 'Admin\RejekiNomplokController@products_edit');
    Route::get('admin/rejeki-nomplok/master/create', 'Admin\RejekiNomplokController@master_create_view');
    Route::get('admin/rejeki-nomplok/master/edit/{id}', 'Admin\RejekiNomplokController@master_edit_view');
    Route::post('admin/rejeki-nomplok/master/submit', 'Admin\RejekiNomplokController@master_submit');
    Route::post('admin/rejeki-nomplok/master/banner', 'Admin\RejekiNomplokController@master_banner');
    Route::post('admin/rejeki-nomplok/master/edit', 'Admin\RejekiNomplokController@master_edit');
    Route::post('admin/rejeki-nomplok/send/point', 'Admin\RejekiNomplokController@send_point');

    // Special Event
    Route::get('admin/raffle/tickets/{id}', 'Admin\TicketRaffleController@list');
    Route::get('admin/raffle/tickets/table/dt', 'Admin\TicketRaffleController@data');

    Route::get('/admin/order/canceled', 'Admin\OrderController@order_canceled');
    Route::get('/admin/order/canceled/dt', 'Admin\OrderController@order_canceled_dt');
    Route::get('/admin/canceled/detail/{id}', 'Admin\OrderController@cancel_detail');
    Route::post('/admin/chart', 'Admin\AdminController@chart');

    Route::get('/admin/test/job', function(){

        // dispatch(new App\Jobs\ProcessExpiredOrder());
        // $transaction_number = 'TRANX49984910';
        // $voucher_code = 'tnatxhjq';

        // $details['transaction_number'] = $transaction_number;
        // $details['voucher_code'] = $voucher_code;
        // // ProcessExpiredOrder::dispatch($transaction_number, $voucher_code)->delay(Orders::expired_order('ID_OVO'));
        // dispatch(new App\Jobs\ProcessExpiredOrder($details));

        dispatch(new App\Jobs\TestQue());

        dd('done bro!');
    });

    //Analytic
    Route::get('/admin/analytic/hot-point', 'Admin\AnalyticController@hot_point_index');
    Route::post('/admin/analytic/hot-point', 'Admin\AnalyticController@hot_point');
    Route::get('/admin/analytic/hot-point/dt', 'Admin\AnalyticController@hotpoint_dt');
    Route::get('/admin/analytic/hot-point/download/{date}', 'Admin\AnalyticController@download_hotpoint');

    Route::get('/admin/analytic/user-bio', 'Admin\AnalyticController@user_bio');
    Route::get('/admcheck-otpin/analytic/get/user/bio', 'Admin\AnalyticController@get_user_bio');
    Route::get('/admin/analytic/get/user/bio', 'Admin\AnalyticController@get_user_bio');
    Route::get('/admin/analytic/get/user/bio/dob', 'Admin\AnalyticController@get_user_bio_dob');
    Route::get('/admin/analytic/get/user/all', 'Admin\AnalyticController@all_user');
    Route::post('/admin/analytic/get/data/product', 'Admin\AnalyticController@get_data_product');
    Route::post('/admin/analytic/get/data/product/dt', 'Admin\AnalyticController@get_dt');
    Route::get('/admin/analytic/product', 'Admin\AnalyticController@product');

    Route::post('/admin/analytic/user-bio', 'Admin\AnalyticController@user_bio');
    Route::get('/admin/analytic/user-bio/download/{date}', 'Admin\AnalyticController@download');

    Route::get('/admin/analytic/status-transaksi', 'Admin\AnalyticController@status_transaksi');
    Route::get('/analytic/status-transaksi/dt', 'Admin\AnalyticController@transaksi_dt');
    Route::post('/admin/analytic/status-transaksi/graph', 'Admin\AnalyticController@transaksi_graph');
    Route::get('/admin/analytic/nru', 'Admin\AnalyticController@nru');
    Route::get('/admin/analytic/cart-wishlist', 'Admin\AnalyticController@cart_wishlist_index');
    Route::post('/admin/analytic/cart-wishlist', 'Admin\AnalyticController@cart_wishlist');
    Route::get('/admin/analytic/voucher', 'Admin\AnalyticController@voucher_index');
    Route::post('/admin/analytic/voucher', 'Admin\AnalyticController@voucher');

    // Coupon
    Route::get('/admin/coupon', 'Admin\CouponController@index');
    Route::get('/admin/coupon/get', 'Admin\CouponController@get_dt');
    Route::get('/admin/coupon/create', 'Admin\CouponController@create_view');
    Route::post('/admin/coupon/create', 'Admin\CouponController@submit');
    Route::get('/admin/coupon/edit/{id}', 'Admin\CouponController@edit_view');
    Route::get('/admin/coupon/detail/get', 'Admin\CouponController@get_detail_dt');
    Route::post('/admin/coupon/edit', 'Admin\CouponController@edit');
    Route::post('/admin/coupon/detail/disable', 'Admin\CouponController@disable_coupon_detail');

    // Partner
    Route::get('/admin/partner', 'Admin\PartnerController@index');
    Route::get('/admin/partner/get', 'Admin\PartnerController@get_dt');
    Route::post('/admin/partner/get/code', 'Admin\PartnerController@get_code');
    Route::get('/admin/partner/create', 'Admin\PartnerController@create_view');
    Route::post('/admin/partner/create', 'Admin\PartnerController@submit');
    Route::get('/admin/partner/edit/{id}', 'Admin\PartnerController@edit_view');
    Route::post('/admin/partner/edit', 'Admin\PartnerController@edit');

    Route::get('/admin/notifications', 'Admin\AdminController@notification');

    Route::get('/admin/special/event', 'Admin\SpecialEventController@index');
    Route::get('/admin/special/event/create', 'Admin\SpecialEventController@create_view');
    Route::post('/admin/special/event/create', 'Admin\SpecialEventController@submit');
    Route::get('admin/special/event/dt', 'Admin\SpecialEventController@special_event_dt');
    Route::get('admin/special/event/edit/{slug}', 'Admin\SpecialEventController@edit_view');
    Route::post('admin/special/event/edit/', 'Admin\SpecialEventController@update');

    // Merchant
    Route::get('/admin/merchant/{id}', 'Admin\VendorController@merchant');
    Route::post('/admin/merchant/product/highlight', 'Admin\VendorController@highlight');
    Route::post('/admin/merchant/banner', 'Admin\VendorController@submit_banner');
    Route::get('/admin/merchant/banner/dt', 'Admin\VendorController@banner_merchant');
    Route::post('/admin/merchant/banner/inactive', 'Admin\VendorController@banner_inactive');
    // Hotdeal Invite
    Route::get('/admin/invite/{code}', 'Admin\InviteController@index');
    Route::get('/admin/invited/user', 'Admin\InviteController@invited_user_dt');
    Route::get('/admin/hotdeal-invite', 'Admin\InviteController@invite_friends');
    Route::get('/admin/invite/user/dt', 'Admin\InviteController@user_dt');

    Route::get('/admin/hotdeal-invite/rules', 'Admin\InviteController@rules');
    Route::get('/admin/invite/rules/dt', 'Admin\InviteController@rule_dt');
    Route::get('/admin/invite/rules/create', 'Admin\InviteController@rule_create');
    Route::post('/admin/invite/rules/submit', 'Admin\InviteController@rule_submit');
    Route::get('/admin/invite/rule/edit/{id}', 'Admin\InviteController@rule_edit');
    Route::post('/admin/invite/rules/update', 'Admin\InviteController@rule_update');

    Route::get('/admin/invite/{code}', 'Admin\InviteController@index');
    Route::get('/admin/invited/user', 'Admin\InviteController@invited_user_dt');

    // Google Analytic
    Route::get('/admin/google-analytic', 'Admin\AdminController@google_analytic');
});

Route::get('/admin/get/province', 'Admin\AdminController@get_province');
Route::post('/promotion/dt', 'Admin\PromotionController@promotion_dt');
Route::get('/check/status', 'Admin\PromotionController@check_status');
Route::get('/update/location', 'Admin\AutoController@insert_location');
Route::get('/php/info', 'Admin\AutoController@info');
Route::get('auth/google', 'Auth\GoogleController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\GoogleController@handleGoogleCallback');
Route::get('/setting/send/sms', 'Admin\AutoController@info');
Route::get('/test/uat', 'Admin\AutoController@uat');
Route::get('/test/invoice', 'Admin\AutoController@test_invoice');
Route::get('/test/notification', 'Admin\AutoController@test_notification');
Route::get('/test/order/create', 'Admin\AutoController@create_order_shipper');
//Route::get('/test/order/create', 'Admin\AutoController@create_order_shipper');
Route::get('/test/pricing', 'Admin\AutoController@pricing');
Route::get('/test/late/delivery', 'Admin\AutoController@late_delivery');
//Route::get('/test/cancel/order', 'Admin\AutoController@cancel_order_shipper');



Route::get('cache-data', function () {
    $user = \Cache::remember('user', 60, function() {
        return User::first();
    });
});

Route::get('/product-detail/{slug}', function (\Illuminate\Http\Request $request){
    $meta = \App\Helpers\Utils::get_product_detail_meta($request->slug);

    return view('welcome', $meta);
});

Route::get('/news-detail/{slug}', function (\Illuminate\Http\Request $request){
    $meta = \App\Helpers\Utils::get_news_detail_meta($request->slug);

    return view('welcome', $meta);

});

Route::any('/{any}', function () {
    $meta = \App\Helpers\Utils::default_meta();

    return view('welcome', $meta);

})->where('any', '.*');


