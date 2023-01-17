<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::get('/user', function(Request $request) {
//    return auth()->user();
//});

// CUSTOMER API WITH LOGIN
Route::get('/order/test', 'Api\OrderController@insert_order');
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/wishlist/create' , 'Api\WishListController@create');
    Route::get('/wishlist/list' , 'Api\WishListController@list');
    Route::put('/wishlist/delete' , 'Api\WishListController@delete');
    Route::post('/wishlist/delete/byarray' , 'Api\WishListController@delete_byarray');

    //cart
    Route::post('/cart/buy/now', 'Api\CartController@buy_now')->middleware('valid.add.to.cart');
    Route::post('/cart/insert', 'Api\CartController@insert')->middleware('valid.add.to.cart');
    Route::get('/cart/get', 'Api\CartController@get');
    Route::post('/cart/delete', 'Api\CartController@delete');
    Route::post('/cart/delete/vendor', 'Api\CartController@deleteVendor');
    Route::post('/cart/mark/checkout', 'Api\CartController@mark_as_checkout');
    Route::post('/cart/save/note', 'Api\CartController@save_note');

    // checkout
    Route::get('/checkout/step/1', 'Api\CheckoutController@get_in_checkout');
    Route::get('/checkout/summary', 'Api\CheckoutController@summary');

    Route::get('/user', function(Request $request) {
        return auth()->user();
    });
    Route::get('/user/total-payment', 'Api\GeneralController@user_total_payment');
    
    Route::get('/vendor', 'Api\merchant\ProfileController@get_vendor');
    Route::post('/logout', 'Api\auth\AuthController@logout');

    /* CREATE ORDER */
    //Route::post('/order', 'Api\OrderController@insert_order');
    
    Route::post('/order', 'Api\OrderController@create_order');
    Route::post('/order/get', 'Api\OrderController@get_order_by_user');
    Route::post('/rejeki-nomplok/get/mycoupons', 'Api\RejekiNomplokController@get_mycoupons');
    Route::get('/rejeki-nomplok/active', 'Api\RejekiNomplokController@active_period');
    
    Route::post('/pendingorderbyid', 'Api\OrderController@pending_order_by_transaction');
    
    
    /* END CREATE ORDER */

    /* REFUND */
    Route::post('/refund/request' , 'Api\RefundController@request');
    Route::post('/refund/invoice' , 'Api\RefundController@refund_by_invoice');
    Route::post('/refund/return/confirmation/' , 'Api\RefundController@return_confirmation');
    Route::post('/refund/cancel/order/confirmation/' , 'Api\RefundController@refund_cancel_order');
    Route::post('/refund/store/account/bank' , 'Api\RefundController@store_account');

    Route::post('/send/otp/forget-pin', 'Api\GeneralController@forget_pin_send_otp');
    Route::post('/check/otp/forget-pin', 'Api\GeneralController@forget_pin_check_otp');

    Route::get('/invite/friend/summary', 'Api\InviteController@summary');
    Route::post('/invite/friend/transaction/history', 'Api\InviteController@transaction_history');
    Route::get('/invite/friend/generate/code', 'Api\InviteController@generateCode');
});
// CUSTOMER API WITH LOGIN
Route::post('/login', 'Api\auth\AuthController@login');
Route::post('/register','Api\auth\AuthController@register');
Route::get('/add-dau', 'Api\auth\AuthController@add_dau');

Route::post('/register/email','Api\auth\AuthController@register_email');

Route::post('/forget','Api\auth\AuthController@forget');
Route::post('/forget/verify','Api\auth\AuthController@verify_forget_otp');
Route::post('/forget/set','Api\auth\AuthController@set_new_password');
// Route::post('/login/google', 'Api\auth\AuthController@google');
// Route::post('/login/facebook', 'Api\auth\AuthController@facebook');
Route::post('/token/get', 'Api\auth\AuthController@get_token');


Route::get('/home','Api\HomeController@index');
Route::post('/admin/banner/create', 'Api\admin\BannerController@create');
Route::post('/admin/login', 'Api\admin\AdminAuthController@login');
//Home
Route::post('/home', 'Api\HomeController@index');
//banner
Route::post('/banner/get', 'Api\BannerController@get_banner');
Route::post('/banner/event/get', 'Api\BannerController@banner_event_get');

// popup banner
Route::get('/banner/popup/login', 'Api\BannerController@login_popup_banner');

//highlight
Route::get('/highlight/get/top', 'Api\HighlightController@get_top_highlight');
Route::get('/highlight/video', 'Api\HighlightController@get_vid_highlight');
Route::get('/highlight/get/bottom', 'Api\HighlightController@get_bottom_highlight');
Route::get('/highlight/get/middle', 'Api\HighlightController@get_middle_highlight');
Route::get('/highlight/get/footer', 'Api\HighlightController@get_footer_highlight');
Route::get('/highlight/get/wishlist', 'Api\HighlightController@wishlist');
Route::post('/highlight/get/random', 'Api\HighlightController@get_random_product');
Route::get('/highlight/get/newest', 'Api\HighlightController@get_newest_product');



//Product
Route::post('/product/suggestion/get', 'Api\ProductController@product_suggestion_get');
Route::post('/product/detail', 'Api\ProductController@detail');

Route::get('/home/strength/get', 'Api\HomeController@strength_get');
Route::get('/product/latest', 'Api\ProductController@get_latest_product');
//Category
Route::post('category/get', 'Api\CategoryController@get');
Route::get('menu/category', 'Api\CategoryController@menu_category');
//flash sale
Route::get('/flash-sale', 'Api\FlashSaleController@flashsale');
Route::get('/flash-event', 'Api\FlashSaleController@flashevent');
Route::get('/flash-sale/detail', 'Api\FlashSaleController@flashsaleAll');
Route::post('/flash-sale-filter', 'Api\FlashSaleController@flashsale_filter');

Route::post('flash-sale/detail/get', 'Api\FlashDetailController@flash_sale_detail');

// Special Event
Route::get('/product/bundling/get', 'Api\SpecialEventController@bundling_products');
Route::post('/bundling/transaction/ticket', 'Api\SpecialEventController@ticket_by_transaction');
Route::get('/product/bundling/detail', 'Api\SpecialEventController@event_detail');
 

//product
Route::post('product/detail/get', 'Api\ProductController@product_detail');
Route::post('product/status', 'Api\ProductController@status_product');

//Messages
Route::get('message/get', 'Api\MessageController@get');

//Merchant Info
Route::post('merchant/detail/get', 'Api\SellerController@merchant_detail');
Route::post('merchant/product/get', 'Api\SellerController@merchant_products');
Route::post('merchant/review/get', 'Api\SellerController@merchant_reviews');

// START PRODUCT ROUTE \\

Route::get('/product/list', 'Api\ProductController@list');
Route::get('/product/dt', 'Api\ProductController@product_dt');
Route::middleware('count')->post('product/detail/{slug}', 'Api\ProductController@detail');

//END PRODUCT ROUTE \\

//Notification
Route::get('notification/get', 'Api\NotificationController@get');
Route::post('notification/allnotif', 'Api\NotificationController@all_notification');
Route::post('notification/mark/read', 'Api\NotificationController@mark_as_read');
//Route::get('menubar-materials', 'Api\GeneralController@headermaterials');

// Highlight Title
Route::get('highlight-title/get', 'Api\GeneralController@get_highlight_title');

// API FOR VENDOR
Route::middleware('auth:sanctum')->group(function() {
    // Dashboard
    Route::get('seller/general' , 'Api\merchant\GeneralController@general');
    Route::post('seller/update-profile' , 'Api\merchant\GeneralController@update_profile');

    // Product
    Route::post('seller/product/create' , 'Api\merchant\ProductController@create');
    Route::post('seller/product/update' , 'Api\merchant\ProductController@update');
    Route::post('/seller/product/list' , 'Api\merchant\ProductController@list');
    Route::post('/seller/delete/galleries' , 'Api\merchant\ProductController@delete_image_by_id');
    Route::post('/seller/upload/galleries' , 'Api\merchant\ProductController@upload_galleries');
    Route::post('seller/product/edit/byslug' , 'Api\merchant\ProductController@product_edit_by_slug');
    Route::get('/seller/product/category', 'Api\merchant\ProductController@category_product');
    Route::post('/seller/product/variant', 'Api\merchant\ProductController@set_variant');
    // Order
    //Route::get('/seller/order/list' , 'Api\merchant\OrderController@list');
    //Route::patch('seller/order/update' , 'Api\merchant\OrderController@update');
    Route::post('seller/order/update' , 'Api\merchant\OrderController@update');
    Route::post('/seller/order/list' , 'Api\merchant\OrderController@list');
    Route::post('/seller/detail/transaction', 'Api\merchant\OrderController@byinvoice');
    Route::post('/seller/label', 'Api\merchant\OrderController@label');
    Route::post('/seller/pickup', 'Api\merchant\OrderController@create_pickup');

    // Refund
    Route::post('/seller/order/refund' , 'Api\merchant\RefundController@refund_seller_list');
    Route::post('/seller/order/refund/by_invoice' , 'Api\merchant\RefundController@refund_by_invoice');
    Route::get('seller/refund/list', 'Api\merchant\RefundController@refund_seller_list');
    Route::post('seller/refund/approve', 'Api\merchant\RefundController@refund_seller_approve');
    Route::post('seller/refund/process', 'Api\merchant\RefundController@refund_seller_process');
    Route::post('seller/refund/cancel', 'Api\merchant\RefundController@refund_seller_cancel');

    Route::post('seller/update/address', 'Api\merchant\ProfileController@update_vendor_address');

    // Dashboard
    Route::post('seller/dashboard/sale-data', 'Api\merchant\GeneralController@sale_data');


});
// END API FOR VENDOR

//profile
Route::post('/profile/save-profile-pictures' , 'Api\ProfileController@save_profile_pictures');
Route::post('/profile/address/add', 'Api\ProfileController@add_address');
Route::post('/profile/address/pinpoint', 'Api\ProfileController@set_pin_point');
Route::post('/profile/address/update', 'Api\ProfileController@update_address');
Route::post('/profile/address/delete', 'Api\ProfileController@delete_address');
Route::post('/profile/address/primary', 'Api\ProfileController@set_primary_address');
Route::get('/profile/address/primary', 'Api\ProfileController@primary_address');
Route::post('/profile/change/password', 'Api\ProfileController@change_password');
Route::get('/profile/check/password', 'Api\ProfileController@check_security');
Route::get('/profile/address/get', 'Api\ProfileController@get_address');
Route::post('/profile/update', 'Api\ProfileController@update_profile');
Route::get('/profile/get', 'Api\ProfileController@get_profile');
Route::get('/menubar-materials', 'Api\ProfileController@menubar_materials');

//location
Route::get('/location/province/get', 'Api\LocationController@province');
Route::post('/location/cities/get', 'Api\LocationController@city');
Route::post('/location/suburbs/get', 'Api\LocationController@suburbs');
Route::post('/location/area/get', 'Api\LocationController@area');


// master
Route::get('/master/status/order', 'Api\FilterController@master_status_order');

// end master
//discount
Route::get('/discount/get', 'Api\admin\DiscountController@get_discount');


//promotion
Route::post('/promotion/get', 'Api\VoucherController@get_promotion');
Route::get('/promotion/get/list', 'Api\VoucherController@get_promotion_list');
Route::get('/promotion/get/category/list', 'Api\VoucherController@get_promotion_by_category');
Route::post('/promotion/detail/get', 'Api\VoucherController@get_promotion_by_id');

//filter
Route::post('/filter/get', 'Api\FilterController@get');
Route::post('/filter/bundling', 'Api\FilterController@get_bundling');
Route::post('/filter/category', 'Api\FilterController@get_category');

//shipment
Route::get('/shipment/get', 'Api\ShipmentController@get');
Route::post('/shipment/check/price', 'Api\ShipmentController@get_pricing');
Route::post('/shipment/tracking/log', 'Api\ShipmentController@track_log');
Route::post('/shipment/callback', 'Api\ShipmentController@webhook_logistic');
//Route::get('/shipment/callback', 'Api\ShipmentController@webhook_logistic');
Route::post('/shipment/order', 'api\ShipmentController@get_order');

//search
Route::post('/search/get', 'Api\SearchController@get');
Route::post('/product/bundling', 'Api\SearchController@bundling');
Route::post('/product/categories', 'Api\SearchController@product_categories');
Route::post('/product/bundling/random', 'Api\SearchController@bundling_random');
Route::post('/search/history/delete', 'Api\SearchController@search_history_delete');
Route::get('/search/get/option', 'Api\SearchController@get_option');
Route::post('/search/recommendation/get', 'Api\SearchController@get_search_recommendations');
Route::post('/search/product', 'Api\SearchController@get_search_product');
Route::get('/popular/product', 'Api\PopularController@popular_product');
Route::get('/popular/category', 'Api\PopularController@popular_category');
//review
Route::post('/review/get', 'Api\ReviewController@get');
Route::post('/review-user/get', 'Api\ReviewController@user_review');
Route::post('/review/submit', 'Api\ReviewController@submit');
Route::post('/review/update', 'Api\ReviewController@update');
Route::delete('/review/delete/{id}', 'Api\ReviewController@delete');
Route::post('/review/carousel', 'Api\ReviewController@carousel');

//Voucher
Route::post('/voucher/vendor/get', 'Api\CouponController@get_coupon_store');
Route::post('/voucher/vendor/claim', 'Api\CouponController@insert_coupon_store');
Route::get('/voucher/hotdeal/get', 'Api\CouponController@get_hotdeal_voucher');
Route::post('/voucher/hotdeal/claim', 'Api\CouponController@claim_hotdeal_coupon');
Route::post('/voucher/remove', 'Api\CouponController@claim_hotdeal_coupon');

//Hotpoint
Route::post('/profile/hotpoint/list', 'Api\ProfileController@hotpoints_list');
Route::post('/hotpoint/use', 'Api\HotpointController@use_hotpoints');
Route::post('/hotpoint/check/password', 'Api\HotpointController@check_password');
Route::post('/hotpoint/use/checkout', 'Api\HotpointController@use_hotpoint_checkout');
Route::post('/hotpoint/send/otp', 'Api\HotpointController@send_otp');
Route::post('/hotpoint/check/otp', 'Api\HotpointController@check_otp');
Route::post('/hotpoint/set/password', 'Api\HotpointController@set_password');
Route::post('/hotpoint/update/password', 'Api\HotpointController@update_password');
Route::get('/hotpoint/get', 'Api\HotpointController@get_hotpoint');

//Order
//Payment
Route::get('/payment/xendit/bank/list', 'Api\Payment\XenditController@get_list_va');
Route::post('/payment/xendit/create/va', 'Api\Payment\XenditController@create_va');
Route::post('/payment/xendit/callback/va', 'Api\Payment\XenditController@callback_va');
Route::get('/payment/xendit/get/payment-method', 'Api\Payment\XenditController@get_payment_method');

Route::get('/payment/method', 'Api\PaymentMethodsController@list');
Route::post('/payment/callback/hotdeal' , 'Api\Payment\CallbackController@callback');
Route::post('/payment/callback/va/created' , 'Api\Payment\CallbackController@va_created_callback');
Route::post('/payment/callback/hotdeal/ewallet' , 'Api\Payment\CallbackController@callback_ewallet');

Route::post('/transaction/unpaid/get/list', 'Api\TransactionController@get');
Route::post('/transaction/unpaid/get/detail', 'Api\TransactionController@detail');
Route::post('/transaction/unpaid/status/get/', 'Api\TransactionController@get_transaction_by_status');
Route::post('/transaction/paid/get/list', 'Api\TransactionController@get_per_invoice');

Route::post('/transaction/invoice/modal', 'Api\TransactionController@invoice_modal');
Route::post('/transaction/detail/transaction', 'Api\TransactionController@byinvoice');
Route::post('/transaction/complete/order', 'Api\TransactionController@complete_order');
Route::post('/transaction/invoice/download', 'Api\TransactionController@download_invoice');
Route::get('/transaction/invoice/download', 'Api\TransactionController@download_invoice');
Route::post('/transaction/continue/payment', 'Api\TransactionController@continue_payment');
Route::post('/cancel-order/data', 'Api\TransactionController@cancel_order_by_invoice');



Route::get('/login/{provider}', 'Api\SocialController@redirectToProvider');

Route::post('/login/{provider}', 'Api\SocialController@login');
Route::get('/login/{provider}/callback', 'Api\SocialController@handleProviderCallback');


Route::get('/logins/{provider}', 'Api\SocialController@redirectToProviderGoogle');

//socialite
//Route::post('sociallogin/{provider}', 'auth\AuthController@SocialSignup');
//Route::post('auth/{provider}', 'OutController@index')->where('vue', '.*');
//Route::post('auth/{provider}/callback', 'OutController@index')->where('vue', '.*');
Route::get('/profile/transaction/get', 'Api\ProfileController@get_transaction');
Route::post('/auth/send/otp', 'Api\auth\AuthController@send_otp');
Route::post('/auth/verify/otp', 'Api\auth\AuthController@verify_otp');
Route::post('/auth/verify/email/otp', 'Api\auth\AuthController@register_verify_otp');
Route::post('/phone/check', 'Api\auth\AuthController@phone_check');

// Footer
Route::get('about-us/get', 'Api\GeneralController@get_about_us');
Route::get('strengths/get', 'Api\GeneralController@get_strengths');
Route::get('faqs/get', 'Api\GeneralController@get_faqs');
Route::get('privacy/get', 'Api\GeneralController@get_privacy');
Route::get('privacy/get/{slug}', 'Api\GeneralController@privacy');
Route::post('newsletter/set', 'Api\GeneralController@newsletter');
//Route::post('/shipment/callback', 'Admin\AutoController@shipper_callback');


// Rejeki Nomplok
Route::get('/rejeki-nomplok/get', 'Api\RejekiNomplokController@get');
Route::post('/rejeki-nomplok/filter', 'Api\RejekiNomplokController@filter');
Route::get('/rejeki-nomplok/banner', 'Api\RejekiNomplokController@banner');
Route::get('/rejeki-nomplok/running-text', 'Api\RejekiNomplokController@running_text');
Route::get('/rejeki-nomplok/winner-text', 'Api\RejekiNomplokController@winner_text');
Route::post('/rejeki-nomplok/get/winners', 'Api\RejekiNomplokController@get_winners');
Route::post('/rejeki-nomplok/period/list', 'Api\RejekiNomplokController@periodList');
Route::get('/rejeki-nomplok/current-week', 'Api\RejekiNomplokController@current_week');

//Page
Route::get('page/get', 'Api\GeneralController@page');
Route::post('/news/detail', 'Api\GeneralController@news_detail');

//Cashtree Coupon
Route::post('set/email', 'Api\Coupon\CouponController@set_email');
Route::post('check/coupon', 'Api\Coupon\CouponController@check_coupon');
Route::post('claim/coupon', 'Api\Coupon\CouponController@claim_coupon');

// Merchant
Route::post('merchant/banner', 'Api\GeneralController@merchant_banner');
Route::post('merchant/highlight/product', 'Api\GeneralController@merchant_highlight_product');
// Hotdeal Invite
Route::post('user/child', 'Api\InviteController@user_child');
Route::get('invite/friend', 'Api\InviteController@referal_code');

