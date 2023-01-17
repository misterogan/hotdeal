<template>
    <div>
        <div class="row-hd checkout tab-column">
            <div class="col-8 col-md-12">
                <checkout-profile @pick_location_status="picked_pin_location = $event"></checkout-profile>
                <checkout-summary @change_bill_payment="update_payment($event)" :pin="picked_pin_location"></checkout-summary>
                <div class="card-lg" v-if="grand_total > 0">
                    <h3 class="nunito-title mbottom-20">Pembayaran</h3>
                    <h5 class="dynamic-title"></h5>
                    <h5 class="dynamic-title unset"></h5>
                    <div class="select-pay mobile-shadow">
                        <div class="default payment" v-if="selected_payment === null">Pilih Metode Pembayaran</div>
                        <div class="default payment selected" v-else>
                            <img width="20" :src="'img/'+selected_payment_data.icon" class="mright-10" alt="">
                            <h5 class="fs-black">{{selected_payment_data.label}}</h5>
                        </div>
                        <div class="payment-method" v-if="Object.keys(payment_gateway).length > 0">
                            <div class="close-div d-none">
                                <img src="/img/grey_bar.svg" alt="">
                            </div>
                            <!-- <h5 class="fs-black mbottom-20 title-mobile">Pilih Pembayaran</h5> -->
                            <div class="mbottom-30" v-for="(item , index) in payment_gateway" :key="index">
                                <div v-if="(grand_total >= 10000)">
                                    <h5 class="fs-black mbottom-10 label-mobile">{{item.label}}</h5>
                                    <!-- <h5 class="fs-black mbottom-10 label-mobile" v-else>{{item.label}}</h5> -->
                                    <div class="payment-list" v-for="(item2 , index2) in item.items" :key="index2">
                                        <div class="d-flex align-center">
                                            <img width="35" :src="'img/'+item2.icon" class="mright-15" alt="">
                                            <span class="fs-black fw-700">{{item2.label}} {{item2.logo}}</span>
                                        </div>
                                        <div class="payment-radio">
                                            <input type="radio" v-model="selected_payment" :value="item2.code" name="virtual_account" :id="item2.code" @click="changePaymentMethod(item2)" />
                                            <label :for="item2.code"></label>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <h5 class="fs-black mbottom-10 label-mobile" v-if="item.label !== 'VIRTUAL ACCOUNT'">{{item.label}}</h5>
                                    <div class="payment-list" v-if="item.label !== 'VIRTUAL ACCOUNT'" v-for="(item2 , index2) in item.items" :key="index2">
                                        <div class="d-flex align-center">
                                            <img width="35" :src="'img/'+item2.icon" class="mright-15" alt="">
                                            <span class="fs-black fw-700">{{item2.label}} {{item2.logo}}</span>
                                        </div>
                                        <div class="payment-radio">
                                            <input type="radio" v-model="selected_payment" :value="item2.code" name="virtual_account" :id="item2.code" @click="changePaymentMethod(item2)" />
                                            <label :for="item2.code"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <img class="dropdown pay" src="/img/dropdown-purple.svg"></img>
                    </div>
                </div>
            </div>
            <div class="col-4 col-md-12 card-purple">
                <h5 class="title-summary">Checkout</h5>
                <div class="co-summary">
                    <div class="co-pricelist">
                        <h6>Total Belanja</h6>
                        <h6>{{total_payment | RupiahFormat}}</h6>
                    </div>
                    <!-- <div class="co-pricelist">
                        <h6>Pot. Ongkir</h6>
                        <h6>- Rp 20.000</h6>
                    </div> -->
                    <div class="co-pricelist">
                        <h6>Ongkir</h6>
                        <h6>{{shipment | RupiahFormat}}</h6>
                    </div>
                    <div class="co-pricelist">
                        <div>Diskon</div>
                        <h6>{{hotdeal_voucher > 0 ? '- Rp ' : 'Rp '}} {{hotdeal_voucher | NumberFormat}}</h6>
                    </div>
                    <div class="co-pricelist">
                        <h6>Asuransi</h6>
                        <h6>{{insurance_fee | RupiahFormat}}</h6>
                    </div>
                    <div class="co-pricelist">
                        <h6>Poin</h6>
                        <h6>{{point_used > 0 ? '-' : ''}} {{point_used | NumberFormat}}</h6>
                    </div>
                    <div class="co-total cart-dark-purple">
                        <h5>Total Harga</h5>
                        <h5 class="total">{{grand_total | RupiahFormat}}</h5>
                    </div>
                    <div class="cart-dark-purple" v-if="parseInt(real_point) > 0">
                        <div class="d-flex align-center justify-between mbottom-10">
                            <h6 class="fw-700">Gunakan hot point</h6>
                            <div class="d-flex">
                                <input type="checkbox" id="checkpoint" v-model="use_point" @change="check_point_balance"/>
                                <label for="checkpoint"></label>
                            </div>
                        </div>
                        <div class="d-flex align-center justify-between">
                            <h6>Total Hotpoint</h6>
                            <div class="d-flex align-center">
                                <img class="mright-5" width="14" src="/img/ic_hotpoint.svg">
                               <h6 class="fw-600">{{ point | NumberFormat}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <button class="use-voucher" href="javascript:void(0)" @click="modal_voucher_merchant()">
                    Gunakan Voucher
                </button> -->
                <button href="javascript:void(0)" @click="modal_voucher_merchant()" class="row-hd align-center cart-discount" id="floating_btn">
                    <img class="ic-promo" src="/img/cart_promo.svg" alt="" width="30">
                    <div class="d-flex align-start flex-column">
                        <h6 class="use-voucher"></h6>
                        <!-- <span class="fs-12 white mobile-notes d-none">Ada 100 Voucher yang bisa digunakan</span> -->
                    </div>
                    <!-- <img class="pointer click-detail" src="/img/ic_arrow_next.svg" alt=""> -->
                </button>
                <div class="floating-co-mobile">
                    <div class="total-co fw-600">
                        <span>Total Tagihan</span>
                        <span class="total">{{grand_total | RupiahFormat}}</span>
                    </div>
                    <button href="javascript:void(0)" @click="create_order()" class="btn-cta m-auto btn-next-pay" id="floating_btn">
                        <img src="img/cart_payment_next_white.svg" alt="">
                        Lanjut Pembayaran
                    </button>
                </div>
            </div>
        </div>
        <profile-phone></profile-phone>
        <modal-hotpoint-confirm @validated="pinVerified($event)"></modal-hotpoint-confirm>
        <modal-otp></modal-otp>
        <modal-voucher @UpdateVocuherHotdeal="UpdateVocuherHotdeal($event)" :voucher_list="voucher_list" :voucher_total_payment="{total_payment : grand_total}" :product_id="13" :method="parentMethod" :pagination="pagination"></modal-voucher>
        <modal-checkout-pending-transaction :data="modal_data" :reload="'true'"></modal-checkout-pending-transaction>
        <modal-payment-ewallet :total_payment="grand_total" @updatePhone=updatePhone($event)></modal-payment-ewallet>
        <modal-forget-pin-confirmation></modal-forget-pin-confirmation>
        <modal-hotpoint></modal-hotpoint>
        
    </div>
    
</template>

<script>
    import ProfilePhone from './modal/ProfilePhone.vue'
    import ModalForgetPinConfirmation from './modal/ModalForgetPinConfirmation.vue'
    import ModalHotpoint from './modal/ModalHotpoint.vue'
    import ModalOtp from './modal/ModalOTP.vue'
    import apiCustomer from '../../apis/Customer'
    import Modal from '../../components/desktop/Modal.vue'
    import VueSlickCarousel from 'vue-slick-carousel'
    import CheckoutProfile from '../desktop/component/checkout/CheckoutProfile.vue'
    import CheckoutSummary from '../desktop/component/checkout/CheckoutSummary.vue'
    import 'vue-slick-carousel/dist/vue-slick-carousel.css'
    import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
    import ModalCheckoutPendingTransaction from './modal/ModalCheckoutPendingTransaction.vue'
    import ModalVoucher from '../../components/desktop/modal/ModalGeneralVoucher.vue'
    import ModalHotpointConfirm from './modal/ModalHotpointConfirm.vue'
    import Message from '../../utils/Message'
    import ModalPaymentEwallet from './modal/ModalPaymentEwallet.vue'

    export default {
        name: "Checkout.vue",
        data(){
            return{
                pin_verified : false,
                order_status : false,
                total_payment : 0,
                grand_total : 0,
                discount : 0,
                real_point : 0,
                point : 0,
                point_used : 0,
                use_point : false,
                voucher : 0 ,
                product_id : '' ,
                voucher_list :{},
                merchant_voucher : 0,
                hotdeal_voucher : 0,
                shipment : 0,
                item_voucher : [],
                btnCreateOrder : true,
                order_data : '',
                order_data_merchant_voucher : {},
                order_data_hotdeal_voucher : {},
                order_data_logistic : '',
                order_data_voucher : '',
                insurances : [],
                insurance_fee : 0,
                payment_gateway : {},
                selected_payment : null,
                selected_payment_data: {},
                modal_data : {},
                phone_number : '',
                picked_pin_location : true,
                pagination : {
                    page : 1,
                    current : 1,
                    total : 1,
                    status : '',
                    date : '',
                    search : '',
                    filter : '',
                    perpage : 10
                },
                is_code: false,
                payment_co : false
            }
        },
        mounted(){
            this.list_payment_gateway()
            this.checkPoint()
        },
        methods: {
            scrollprofile(){
                $('html, body').animate({
                    scrollTop: $("#profile-pemesanan").offset().top - 140
                }, 'slow');
            },
            parentMethod(value) {
                this.voucher_list = {};
                if (this.timer) {
                    clearTimeout(this.timer);
                    this.timer = null;
                }
                this.timer = setTimeout(() => {
                    apiCustomer.GeneralVouchers({payment : this.grand_total, pagination: this.pagination}).then( response => {
                        this.voucher_list = response.data.data.vouchers
                        this.pagination.current_page = response.data.data.current_page
                        this.pagination.total_page = response.data.data.total
                    }); 
                }, 500);
            },
            scrollsummary(){
                $('html, body').animate({
                    scrollTop: $("#summary-transaksi").offset().top - 140
                }, 'slow');
            },
            scrollpayment(){
                $('html, body').animate({
                    scrollTop: $("#payment").offset().top - 140
                }, 'slow');
            },
            modal_voucher_merchant(){
                this.voucher_list = {};
                apiCustomer.GeneralVouchers({payment : this.grand_total, pagination: this.pagination}).then( response => {
                    this.voucher_list = response.data.data.vouchers;
                    this.pagination.current_page = response.data.data.current_page
                    this.pagination.total_page = response.data.data.total

                    $("#general_voucher_card_modal").fadeIn(function () {
                        $("#general_voucher_card_modal").addClass('overflow-scroll');
                        $("body").addClass('overflow-hidden');
                    });
                });  
            },
            modal_payment_ewallet(){
                $("#ewallet_payment_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                     $("#ewallet_payment_modal").addClass('overflow-scroll');
                }); 
            },
            show_otp_warning(){
                $("#otp_warning_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_order(){
                $("#waiting_payment_modal").fadeIn(function () {
                    $("#waiting_payment_modal").addClass('overflow-scroll');
                    // $("body").addClass('overflow-hidden');
                });
            },
            update_payment(event){
                this.insurances = event.insurances
                this.insurance_fee = this.f_insurance_fee();
                this.use_point = false
                this.check_point_balance()
                this.order_data_merchant_voucher = event.merchant_voucher
                this.order_data_logistic = event.couriers
                this.order_data_voucher = event.voucher
                this.merchant_voucher = this.TotalmerchantVoucher()
                this.total_payment = event.total_payment;
                this.grand_total= ((event.total_payment + event.shipment + this.insurance_fee) - this.hotdeal_voucher - event.discount - this.merchant_voucher - this.point_used);
                this.order_status = event.order_status
                this.voucher = (this.merchant_voucher)
                this.shipment = event.shipment
                this.discount = event.discount
                this.product_id = event.product_id
                if(this.grand_total <= 10000){
                    this.selected_payment = null
                }
            },
            f_insurance_fee(){
                    return this.insurances.reduce((value, n) => {
                        return n.applied == true ? value += n.fee : value += 0;
                    }, 0);
            },
            TotalmerchantVoucher(){
                if(Object.keys(this.order_data_merchant_voucher).length > 0){
                    return this.order_data_merchant_voucher.reduce((value, n) => {
                            return value += parseInt(n.item.voucher_value);
                    }, 0);
                }
                return 0;
            },
            create_order(){
                if(this.btnCreateOrder !== true){
                    return ;
                }
                if(this.order_status !== true){
                    return Message.alert("Silahkan pilih pengiriman" ,'Informasi');
                }
                if(this.grand_total > 0){
                    if(this.selected_payment === null){
                        return  Message.alert("Silahkan pilih metode pembayaran",'Informasi');
                    }
                }
                if(this.selected_payment == 'ID_OVO'){
                    if(this.phone_number.length < 11){
                        return this.modal_payment_ewallet();
                    }
                }
                this.btnCreateOrder = false;
                $('#ewallet_payment_modal').hide()
                $("#modal_load").fadeIn();
                apiCustomer.createOrder({payment_gateway : this.selected_payment , product_paymnet : this.total_payment , total_payment : this.grand_total , voucher : this.order_data_voucher ,merchant_voucher : this.order_data_merchant_voucher , logistic : this.order_data_logistic , voucher_hotdeal : this.order_data_hotdeal_voucher , point_used  : this.point_used , phone : this.phone_number , insurances : this.insurances , insurance_fee : this.insurance_fee }).then( response => {
                    this.phone_number = '';
                   if(response.data.code === 200){
                       fbq('track', 'Purchase', {value: this.total_payment, currency: 'IDR'});
                       if(response.data.data.redirect == true){
                           return window.location.href = response.data.data.url
                       }
                        this.modal_data = response.data.data.order_data;
                        $("#waiting_payment_modal").fadeIn(function () {
                            $("#waiting_payment_modal").addClass('overflow-scroll');
                        });
                   }else if(response.data.code === 207){
                       this.show_otp_warning()
                       this.btnCreateOrder = true;
                        $("#modal_load").fadeOut();
                   }else{
                       this.btnCreateOrder = true;
                       Message.alert(response.data.message , "Informasi");
                        $("#modal_load").fadeOut();
                   }
                    $("#modal_load").fadeOut();
                });
            },
            list_payment_gateway(){
                apiCustomer.list_PG().then(response => {
                    this.payment_gateway = response.data.data
                })
            },
            checkPoint(){
                apiCustomer.profile().then(response => {
                    this.point = response.data.point
                    this.real_point = response.data.point
                })
            },
            UpdateVocuherHotdeal(v){
                this.hotdeal_voucher = parseInt(v.voucher);
                console.log(v)
                this.is_code = v.voucher_hotdeal.is_code;
                this.grand_total = ((this.total_payment + this.shipment + this.insurance_fee) - this.hotdeal_voucher - this.discount - this.merchant_voucher);
                if(this.grand_total <= 10000){
                    this.selected_payment = null;
                }
                this.order_data_hotdeal_voucher = v.voucher_hotdeal
                $("#general_voucher_card_modal").fadeOut(function () {
                    $("body").addClass('overflow-hidden')
                });
            },
            check_point_balance(){
                if(!this.pin_verified && this.use_point){
                    this.pin_modal()
                    return 
                }
                if(this.use_point){
                    if((this.grand_total + this.point_used)  <= this.point){
                        this.point_used = (this.grand_total + this.point_used)
                        this.point = this.point - this.point_used
                    }else{
                        this.point_used = this.point
                        this.point = 0
                    }
                }else{
                    this.point_used = 0
                    this.point = this.real_point
                }
                this.grand_total = ((this.total_payment + this.shipment + this.insurance_fee) - this.hotdeal_voucher - this.discount - this.merchant_voucher - this.point_used);
            },
            pin_modal(){
                $("#confirm_pin_point").fadeIn(function () {
                    $("#confirm_pin_point").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            forget_pin_modal(){
            },
            pinVerified(e){
                if(e.status == true){
                    this.pin_verified = true
                    this.check_point_balance()
                    
                }else{
                    this.pin_verified = false
                    this.use_point = false
                }
                $("#confirm_pin_point").fadeOut(function () {
                    $("#confirm_pin_point").removeClass('overflow-scroll');
                    $("body").removeClass('overflow-hidden');
                });
            },
            changePaymentMethod(data){
                this.selected_payment_data = data
                window.scrollTo(0,0);
            },
            updatePhone(event){
                if(!event.alert){
                    return ;
                }
                this.phone_number = event.phone;
                if(this.phone_number.length < 11){
                   return Message.alert("Nomor telepon minimal 11 angka." , "Informasi" , 2000)
                }
                this.create_order()
            }

        },
        components:{
            Modal,
            VueSlickCarousel,
            CheckoutProfile,
            CheckoutSummary,
            ModalCheckoutPendingTransaction,
            ModalVoucher,
            ModalOtp,
            ProfilePhone,
            ModalHotpointConfirm,
            Message,
            ModalPaymentEwallet,
            ModalForgetPinConfirmation,
            ModalHotpoint
        },
    }
</script>

<style scoped>

</style>