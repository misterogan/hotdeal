import Api from '../apis/Api';
import Csrf from '../apis/Csrf'

export default{
    async profile(){
        return Api.get('/api/user');
    },
    async user_total_payment(){
        return Api.get('/api/user/total-payment');
    },
    async get_highlight_title(){
        return Api.get('/api/highlight-title/get');
    },
    async get_login_banner(){
        return Api.get('/api/banner/popup/login');
    },
    menubar_materials(){
        Csrf.getCookie();
        return Api.get('/api/menubar-materials');
    },
    async wish(form){
        await Csrf.getCookie();
        return Api.post('/api/wishlist/create', form);
    },
    wishlist(form){
        Csrf.getCookie();
        return Api.get('/api/wishlist/list', form);
    },
    async removeWishlist(form){
        await Csrf.getCookie();
        return Api.post('/api/wishlist/delete/byarray', form);
    },
    highlight(){
        Csrf.getCookie();
        return Api.get('/api/highlight/get/top');
    },
    highlight_random_product(form){
        Csrf.getCookie();
        return Api.post('/api/highlight/get/random',form);
    },
    highlight_new_product(){
        Csrf.getCookie();
        return Api.get('/api/highlight/get/newest');
    },
    highlightMiddle(){
        Csrf.getCookie();
        return Api.get('/api/highlight/get/middle');
    },
    highlightBottom(){
        Csrf.getCookie();
        return Api.get('/api/highlight/get/bottom');
    },
    highlightFooter(){
        Csrf.getCookie();
        return Api.get('/api/highlight/get/footer');
    },
    highlightVideo(){
        Csrf.getCookie();
        return Api.get('/api/highlight/video');
    },
    highlightWishlist(){
        Csrf.getCookie();
        return Api.get('/api/highlight/get/wishlist');
    },
    productRecomedation(form){
        Csrf.getCookie();
        return Api.post('/api/product/suggestion/get', form);
    },
    merchantDetail(form){
        Csrf.getCookie();
        return Api.post('/api/merchant/detail/get', form);
    },
    merchantProduct(form){
        Csrf.getCookie();
        return Api.post('/api/merchant/product/get', form);
    },
    merchantReview(form){
        Csrf.getCookie();
        return Api.post('/api/merchant/review/get', form);
    },
    merchantHighlightProduct(form){
        Csrf.getCookie();
        return Api.post('/api/merchant/highlight/product', form);
    },
    merchantBanner(form){
        Csrf.getCookie();
        return Api.post('/api/merchant/banner', form);
    },
    searchProduct(form){
        Csrf.getCookie();
        return Api.post('/api/search/get' , form);
    },
    searchProductBundling(form){
        Csrf.getCookie();
        return Api.post('/api/product/bundling' , form);
    },
    productBundlingRandom(){
        Csrf.getCookie();
        return Api.post('/api/product/bundling/random');
    },
    deleteHistory(form){
        Csrf.getCookie();
        return Api.post('/api/search/history/delete' , form);
    },
    popularProduct(){
        Csrf.getCookie();
        return Api.get('/api/popular/product');
    },
    categoryPopular(){
        Csrf.getCookie();
        return Api.get('/api/popular/category');
    },
    async filterProduct(form){
        await Csrf.getCookie();
        return Api.post('/api/filter/get' , form);
    },
    filterProductBundling(form){
        Csrf.getCookie();
        return Api.post('/api/filter/bundling' , form);
    },
    filterCategory(form){
        Csrf.getCookie();
        return Api.post('/api/filter/category' , form);
    },
    flashsale(){
        Csrf.getCookie();
        return Api.get('/api/flash-sale');
    },
    flashEvent(){
        Csrf.getCookie();
        return Api.get('/api/flash-event');
    },
    flashsaleAll(){
        Csrf.getCookie();
        return Api.get('/api/flash-sale/detail');
    },
    async flashsaleFilter(form){
        await Csrf.getCookie();
        return Api.post('/api/flash-sale-filter', form);
    },
    productsByCategory(form){
        Csrf.getCookie();
        return Api.post('/api/product/categories' , form);
    },
    async get_product_by_slug(form){
        await Csrf.getCookie();
        return Api.post('/api/product/detail' , form);
    },
    async get_page_by_slug(form){
        await Csrf.getCookie();
        return Api.post('/api/news/detail' , form);
    },
    async buyNow(form){
        await Csrf.getCookie();
        return Api.post('/api/cart/buy/now' , form);
    },
    async cart(form){
        await Csrf.getCookie();
        return Api.post('/api/cart/insert' , form);
    },
    async saveNote(form){
        await Csrf.getCookie();
        return Api.post('/api/cart/save/note' , form);
    },
    async deleteCart(form){
        await Csrf.getCookie();
        return Api.post('/api/cart/delete' , form);
    },
    async deleteCartVendor(form){
        await Csrf.getCookie();
        return Api.post('/api/cart/delete/vendor' , form);
    },
    async list_cart(){
        await Csrf.getCookie();
        return Api.get('/api/cart/get');
    },
    async markCheckout(form){
        await Csrf.getCookie();
        return Api.post('/api/cart/mark/checkout' , form);
    },
    async checkoutSummary(){
        await Csrf.getCookie();
        return Api.get('/api/checkout/summary');
    },
    async get_address_list(){
        await Csrf.getCookie();
        return Api.get('/api/profile/address/get');
    },
    async save_address(form){
        await Csrf.getCookie();
        return Api.post('/api/profile/address/add' , form);
    },
    async set_pinpoint(form){
        await Csrf.getCookie();
        return Api.post('/api/profile/address/pinpoint' , form);
    },
    async update_address(form){
        await Csrf.getCookie();
        return Api.post('/api/profile/address/update' , form);
    },
    async remove_address(form){
        await Csrf.getCookie();
        return Api.post('/api/profile/address/delete' , form);
    },
    async setAsPrimary(form){
        await Csrf.getCookie();
        return Api.post('/api/profile/address/primary' , form);
    },
    async primaryAddress(form){
        await Csrf.getCookie();
        return Api.get('/api/profile/address/primary' , form);
    },
    async saveProfile(form){
        await Csrf.getCookie();
        return Api.post('/api/profile/update' , form);
    },
    async merchantVouchersbyId(form){
        await Csrf.getCookie();
        return Api.post('/api/voucher/vendor/get' , form);
    },
    async GeneralVouchers(form){
        await Csrf.getCookie();
        return Api.post('/api/promotion/get' , form);
    },
    async checkMerchantVoucher(form){
        await Csrf.getCookie();
        return Api.post('/api/voucher/vendor/claim' , form);
    },
    async checkHotdelVoucher(form){
        await Csrf.getCookie();
        return Api.post('/api/voucher/hotdeal/claim' , form);
    },
    async createOrder(form){
        await Csrf.getCookie();
        return Api.post('/api/order' , form);
    },
    async transactionByInvoiceNumber(form){
        await Csrf.getCookie();
        return Api.post('/api/transaction/detail/transaction' , form);
    },
    async pendingTransaction(form){
        await Csrf.getCookie();
        return Api.post('/api/transaction/unpaid/get/list' , form);
    },
    async transactionList(form){
        await Csrf.getCookie();
        return Api.post('/api/transaction/paid/get/list' , form);
    },
    async completeOrder(form){
        return Api.post('/api/transaction/complete/order' , form);
    },
    async continuePayment(form) {
        await Csrf.getCookie();
        return Api.post('/api/transaction/continue/payment' , form)
    },
    Banners(){
         Csrf.getCookie();
        return Api.post('/api/banner/get');
    },
    async list_PG(){
        await Csrf.getCookie();
        return Api.get('/api/payment/method');
    },
    async detailPendingOrder(form){
        await Csrf.getCookie();
        return Api.post('/api/pendingorderbyid' , form);
    },
    async getTransactions() {
        await Csrf.getCookie();
        return Api.get('/api/profile/transaction/get')
    },

    async searchHistory(){
        await Csrf.getCookie();
        return Api.get('/api/search/get/option');
    },
    async promoList(){
        await Csrf.getCookie()
        return Api.get('/api/promotion/get/list')
    },
    async aboutUs() {
        await Csrf.getCookie();
        return Api.get('/api/about-us/get')
    },
    async strengths() {
        await Csrf.getCookie();
        return Api.get('/api/strengths/get')
    },
    async get_faqs() {
        await Csrf.getCookie();
        return Api.get('/api/faqs/get')
    },
    async get_privacy() {
        await Csrf.getCookie();
        return Api.get('/api/privacy/get')
    },
    async privacy_policy() {
        await Csrf.getCookie();
        return Api.get('/api/privacy/get/privacy-policy')
    },
    async refund_policy() {
        await Csrf.getCookie();
        return Api.get('/api/privacy/get/refund-policy')
    },
    async shipping_policy() {
        await Csrf.getCookie();
        return Api.get('/api/privacy/get/shipping-policy')
    },
    async terms_of_service() {
        await Csrf.getCookie();
        return Api.get('/api/privacy/get/terms-of-service')
    },
    async bundling_products() {
        await Csrf.getCookie();
        return Api.get('/api/product/bundling/get')
    },
    async detail_event() {
        await Csrf.getCookie();
        return Api.get('/api/product/bundling/detail')
    },
    async user_raffle_tickets(form) {
        await Csrf.getCookie();
        return Api.post('/api/bundling/transaction/ticket', form)
    },

    async sendOtp(form){
        return Api.post('/api/auth/send/otp' , form);
    },
    async VerifyOtp(form){
        return Api.post('/api/auth/verify/otp' , form);
    },
    async getReviews(form) {
        await Csrf.getCookie();
        return Api.post('/api/review/get' , form);
    },
    async reviewCarousel(form) {
        await Csrf.getCookie();
        return Api.post('/api/review/carousel' , form);
    },
    async getUserReview(form) {
        await Csrf.getCookie();
        return Api.post('/api/review-user/get' , form);
    },
    async saveReviewProduct(form) {
        await Csrf.getCookie();
        return Api.post('/api/review/submit' , form);
    },
    async updateReviewProduct(form) {
        await Csrf.getCookie();
        return Api.post('/api/review/update' , form);
    },
    async searchRecommendations(form) {
        await Csrf.getCookie();
        return Api.post('/api/search/recommendation/get' , form);
    },
    async searchProducts(form) {
        await Csrf.getCookie();
        return Api.post('/api/search/product' , form);
    },
    async change_password(form){
        await Csrf.getCookie();
        return Api.post('/api/profile/change/password' , form);
    },
    async create_password(form){
        await Csrf.getCookie();
        return Api.post('/api/profile/change/password' , form);
    },
    async download_invoice(form) {
        await Csrf.getCookie();
        return Api.post('/api/transaction/invoice/download' , form, {
            responseType: 'arraybuffer'
        });
    },
    async saveProfilePictures(form){
        await Csrf.getCookie();
        return Api.post('/api/profile/save-profile-pictures' , form);
    },
    async check_password(form){
        await Csrf.getCookie();
        return Api.get('/api/profile/check/password' , form);
    },
    async submitFormRefundTransaction(form){
        await Csrf.getCookie();
        return Api.post('/api/refund/request' , form);
    },
    async refundbyInvoice(form){
        await Csrf.getCookie();
        return Api.post('/api/refund/invoice' , form);
    },
    async submitRefundConfirmation(form){
        await Csrf.getCookie();
        return Api.post('/api/refund/return/confirmation' , form);
    },
    async submitRefundCancelOrder(form){
        await Csrf.getCookie();
        return Api.post('/api/refund/cancel/order/confirmation' , form)
    },
    async submitRefundAccountBank(form){
        await Csrf.getCookie();
        return Api.post('/api/refund/store/account/bank' , form);
    },
    async pointList(form){
        await Csrf.getCookie();
        return Api.post('/api/profile/hotpoint/list' , form);
    },
    async savePin(form){
        await Csrf.getCookie();
        return Api.post('/api/hotpoint/set/password' , form);
    },
    async updatePin(form){
        await Csrf.getCookie();
        return Api.post('/api/hotpoint/update/password' , form);
    },
    async modalInvoice(form) {
        await Csrf.getCookie();
        return Api.post('/api/transaction/invoice/modal' , form);
    },
    async validatePin(form) {
        await Csrf.getCookie();
        return Api.post('/api/hotpoint/check/password' , form);
    },
    async sendPinOTP(form) {
        await Csrf.getCookie();
        return Api.post('/api/send/otp/forget-pin' , form);
    },
    async checkPinOTP(form) {
        await Csrf.getCookie();
        return Api.post('/api/check/otp/forget-pin' , form);
    },
    async couponList(form) {
        await Csrf.getCookie();
        return Api.post('/api/rejeki-nomplok/get/mycoupons' , form);
    },
    activePeriod(){
        Csrf.getCookie();
        return Api.get('/api/rejeki-nomplok/active');
    },
    async winner(form) {
        await Csrf.getCookie();
        return Api.post('/api/rejeki-nomplok/get/winners' , form);
    },
    weekList(form) {
        Csrf.getCookie();
        return Api.post('/api/rejeki-nomplok/period/list' , form);
    },
    filterRejekiNomplok(form) {
        Csrf.getCookie();
        return Api.post('/api/rejeki-nomplok/filter' , form);
    },
    productList(page = 0, filter) {
        Csrf.getCookie();
        return Api.get('/api/rejeki-nomplok/get?page='+page+'&filter='+filter);
    },
    rejekiNomplokBanner() {
        Csrf.getCookie();
        return Api.get('/api/rejeki-nomplok/banner');
    },
    rejekiNomplokRunningText() {
        Csrf.getCookie();
        return Api.get('/api/rejeki-nomplok/running-text');
    },
    winnerText() {
        Csrf.getCookie();
        return Api.get('/api/rejeki-nomplok/winner-text');
    },
    currentWeek() {
        Csrf.getCookie();
        return Api.get('/api/rejeki-nomplok/current-week');
    },
    async cancelOrderbyInvoice(form){
        await Csrf.getCookie();
        return Api.post('/api/cancel-order/data' , form);
    },
    async checkPhoneNumber(form){
        await Csrf.getCookie();
        return Api.post('/api/phone/check' , form);
    },
    async voucherRedeem(form){
        await Csrf.getCookie();
        return Api.post('/api/check/coupon' , form);
    },
    async voucherClaim(form){
        await Csrf.getCookie()
        return Api.post('/api/claim/coupon' , form)
    },
    categoriesMenu(){
        Csrf.getCookie()
        return Api.get('/api/menu/category')
    },
    inviteSummary(){
       Csrf.getCookie()
       return Api.get('/api/invite/friend/summary') 
    },
    inviteHistory(form){
        Csrf.getCookie()
        return Api.post('/api/invite/friend/transaction/history' , form) 
    },
    inviteGenerateCode(){
        Csrf.getCookie()
        return Api.get('/api/invite/friend/generate/code') 
    },
    getLatestProduct(){
        Csrf.getCookie()
        return Api.get('/api/product/latest') 
    }
}
