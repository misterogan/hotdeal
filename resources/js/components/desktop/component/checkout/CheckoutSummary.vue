<template>
    <div class="card-lg mbottom-20">
        <div class="co-order mobile-shadow">
            <h3 class="nunito-title">Pesanan</h3>
            <h5 class="fp-black mbottom-20">{{summary.total}} Barang</h5>
            <div class="border-bottom-grey"></div>
            <div v-if="!is_loading">
                <div class="co-summary" v-if="Object.keys(summary).length > 0">
                    <div class="co-list" v-for="(item, index) in summary.summary " :key="index">
                        <profile-seller :seller="item.vendor"></profile-seller>
                        <div class="co-item" v-for="(product , iproduct) in item.product_detail" :key="iproduct">
                            <router-link :to="'/product-detail/'+product[0].product_detail.product.slug">
                                <img :src="product[0].product_detail.thubmnail.link" :alt="product[0].product_detail.product.name">  
                            </router-link>
                            <div class="co-item-details">
                                <router-link :to="'/product-detail/'+product[0].product_detail.product.slug">
                                    <h5 class="fs-black mbottom-10">{{product[0].product_detail.product.name}}</h5>
                                </router-link>
                                <div class="d-flex align-center mbottom-10">
                                    <!-- <s class="fs-grey fs-18 mright-5" v-if="product[0].product_detail.promotion.nominal > 0">Rp {{product[0].product_detail.promotion.nominal | NumberFormat}}</s> -->
                                    <s class="fs-grey fs-18 mright-5" v-if="product[0].product_detail.promotion.nominal > 0">{{product[0].product_detail.label_price}}</s>
                                    <h5 class="fp-pink fs-18 mright-5">{{product[0].product_detail.label_face_price}}</h5>
                                    <div class="discount" v-if="product[0].product_detail.promotion.value > 0">{{product[0].product_detail.promotion.value}}%</div>
                                </div>
                                <div class="d-flex align-center item-detail-co">
                                    <div class="d-flex align-center">
                                        <div v-if="product[0].product_detail.variant_k_1 !=''">
                                            <span class="variant">{{product[0].product_detail.variant_v_1}}</span>
                                        </div>
                                        <div v-if="product[0].product_detail.variant_k_2 !='' ">
                                            <span class="variant"> {{product[0].product_detail.variant_v_2}}</span>
                                        </div>    
                                    </div>
                                    <div class="d-flex align-center">
                                        <b class="fs-black fs-12">Berat :</b> &nbsp; <span class="fs-black fs-12">{{product[0].product_detail.product.weight}} gram</span>   
                                    </div>
                                    <div class="tag">
                                        <div class="free-shipping"></div>
                                        <div class="voucher-available"></div>
                                        <div class="badge-rejeki-nomplok" v-if="item.product_detail.in_rejeki_nomplok == true"></div>
                                    </div>
                                </div>
                                <div class="d-flex align-center mtop-10" v-if="product[0].notes != null">
                                    <b class="fs-black fs-12">Catatan :</b> &nbsp; <span class="fs-black fs-12">{{product[0].notes}}</span>   
                                </div>
                            </div>
                        </div>
                        <checkout-courier :index="index" @courier_selected="selected_courier($event)" :logistics="item" :pin="pin" ></checkout-courier>
                        <div class="insurance">
                            <div class="check-insurance">
                                <input type="checkbox" :id="'check-insurance-'+item.vendor.id"  :checked="logistic_insurance[item.vendor.id] != undefined &&  logistic_insurance[item.vendor.id].applied == true ? true : false" :disabled="logistic_insurance[item.vendor.id] != undefined &&  logistic_insurance[item.vendor.id].applied == true ? true : false" @click="include_insurance(item.vendor.id , 'check-insurance-'+item.vendor.id)" />
                                <label class="fs-black fw-600" :for="'check-insurance-'+item.vendor.id"></label>
                                <span class="mleft-15 fw-700 fs-black">Asuransi</span>
                            </div>
                            <div class="fs-black fw-400">
                                <input type="hidden" :value="fake_insurance_var" />
                                <span class="fs-black fw-400">{{ logistic_insurance[item.vendor.id] == undefined ? 0 : logistic_insurance[item.vendor.id].fee  | RupiahFormat}}</span>
                            </div>
                        </div>
                        <div class="use-store-voucher" href="javascript:void(0)" @click="merchant_vouchers(item.vendor.id, item.product_detail[0])">
                            <img width="22" class="d-block mright-15" src="/img/ic_voucher.svg" alt="">
                            Gunakan Voucher Toko
                        </div>
                        <div class="co-sub-total">
                            <span>Subtotal</span>
                            <span>Rp {{item.total_payment | NumberFormat}}</span>
                        </div>
                    </div> 
                </div>
                <div v-else >
                    <blank-page :message="'Keranjang Belanja Anda Kosong'" :image="'img/animation_empty_cart.svg'"></blank-page>
                </div>     
            </div>
            <div v-else>
                <div class="ph-row justify-between mbottom-10">
                    <div class="ph-col-2 ph-h100 bg-placeholder mright-10"></div>
                    <div class="ph-col-9 ph-h100 bg-placeholder mright-10"></div>
                    <div class="ph-col-1 ph-h100 bg-placeholder mright-10"></div>
                </div>
            </div>
        </div>
        <modal-voucher @UpdateVocuherMerchant="UpdateVocuherMerchant($event)" :voucher_list="voucher_list" :voucher_total_payment="voucher_total_payment"></modal-voucher>
    </div>
</template>
<script>
    import ModalVoucher from '../../modal/ModalVoucher.vue'
    import apiCustomer from '../../../../apis/Customer'
    import CheckoutCourier from './CheckoutCourier.vue'
    import ProfileSeller from '../ProfileSeller.vue'
    import BlankPage from '../../BlankPage.vue'

    export default {
        components :{
            CheckoutCourier,
            ProfileSeller,
            ModalVoucher,
            BlankPage
        },
        props :['pin'],
        name: "CheckoutSummary.vue",
        data(){
            return{
                is_loading : true,
                summary :{},
                discount : 0,
                shipment : 0,
                courier_index : [],
                couriers : [],
                merchant_voucher : [],
                voucher : [],
                voucher_list : {},
                voucher_total_payment : 0,
                order_status : false,
                error_checkout_message :'',
                blank_page: '',
                logistic_insurance : [],
                fake_insurance_var : 0,
            }
        },
        mounted(){
            apiCustomer.checkoutSummary().then( response => {
                this.is_loading = false;
                this.summary = response.data.data;
                this.$emit('change_bill_payment' , {product_id: this.summary.summary, total_payment : this.summary.payment , discount : this.discount , merchant_voucher : this.merchant_voucher ,  voucher : this.voucher , shipment : this.shipment , couriers : this.couriers , order_status: this.order_status , insurances : this.logistic_insurance });
                if(this.summary.summary.length < 1){
                    this.blank_page();
                }
            });  
        },
        methods: {
            include_insurance(vendor_id , id , type = null){
                if(type != null){
                    return document.getElementById(id).checked = false;
                }
                if(this.logistic_insurance[vendor_id] != undefined){
                    if(this.logistic_insurance[vendor_id].must_use_insurance == true){
                        return;
                    }
                    this.logistic_insurance[vendor_id].applied = !this.logistic_insurance[vendor_id].applied
                }else{
                    document.getElementById(id).checked = false;
                }
                this.$emit('change_bill_payment' , {total_payment : this.summary.payment , discount : this.discount , merchant_voucher : this.merchant_voucher ,  voucher : this.voucher , shipment : this.shipment , couriers : this.couriers , order_status: this.order_status ,  insurances : this.logistic_insurance});
            },
            selected_courier(event){
                let ins = this.logistic_insurance;
                const c = this.couriers
                if(c.some(c => c.vendor === event.vendor)){
                    this.couriers.map((item, index) => {
                        if(item.vendor === event.vendor){
                            this.couriers[index] = event;
                            if(event.detail.must_use_insurance == true){
                                this.logistic_insurance[event.vendor] = {id : event.vendor ,fee : event.detail.insurance_fee , applied : true , must_use_insurance : event.detail.must_use_insurance};
                            }else{
                                this.logistic_insurance[event.vendor] = {id : event.vendor ,fee : event.detail.insurance_fee , applied : false , must_use_insurance : event.detail.must_use_insurance};
                                this.include_insurance(event.vendor , 'check-insurance-'+event.vendor ,1)

                            }
                            this.fake_insurance_var = event.detail.insurance_fee
                        }
                    })
                }else{
                    this.couriers.push(event);

                    if(event.detail.must_use_insurance == true){
                        this.logistic_insurance[event.vendor] = {id : event.vendor ,fee : event.detail.insurance_fee , applied : true , must_use_insurance : event.detail.must_use_insurance};
                    }else{
                        this.logistic_insurance[event.vendor] = {id : event.vendor ,fee : event.detail.insurance_fee , applied : false , must_use_insurance : event.detail.must_use_insurance};
                    }
                    //this.logistic_insurance[event.vendor] = {id : event.vendor ,fee : event.detail.insurance_fee , applied : false};
                    this.fake_insurance_var = event.detail.insurance_fee
                }

                if(Object.keys(this.couriers).length === Object.keys(this.summary.summary).length){
                    this.order_status =  true
                }
                this.shipment = this.TotalShipment()
                $('.insurance').css('display', 'flex');
                this.$emit('change_bill_payment' , {total_payment : this.summary.payment , discount : this.discount , merchant_voucher : this.merchant_voucher ,  voucher : this.voucher , shipment : this.shipment , couriers : this.couriers , order_status: this.order_status ,  insurances : this.logistic_insurance});
            },
            show_modal(){
                $("#change_address_modal").fadeIn(function () {
                    $("#change_address_modal").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            merchant_vouchers(merchant , total_payment){
                
              const t_payment = total_payment.reduce((price, n) => {
                    return price += (parseInt(n.product_detail.face_price) * n.quantity);
               }, 0);
                apiCustomer.merchantVouchersbyId({vendor_id : merchant , total_payment : t_payment}).then( response => {
                    this.voucher_list = response.data.data.coupons;
                    this.voucher_total_payment = response.data.data.total_payment
                    this.show_modal_voucher();
                });  
            },
            show_modal_voucher(){
                $("#general_voucher_card_modal").fadeIn(function () {
                    $("#general_voucher_card_modal").addClass('overflow-scroll');
                    // $("body").addClass('overflow-hidden');
                });
            },
            TotalShipment : function(){
               return this.couriers.reduce((price, n) => {
                    return price += parseInt(n.price);
               }, 0);

            },
            UpdateVocuherMerchant(v){
                if(Object.keys(this.merchant_voucher).length > 0){
                    const c = this.merchant_voucher;
                    if(c.some(c => c.vendor_id === v.vendor_id)){
                        this.merchant_voucher.map((item, index) => {
                            if(item.vendor_id === v.vendor_id){
                                this.merchant_voucher[index].item = v.item;
                            }
                        })
                    }else{
                        this.merchant_voucher.push(v);
                    }
                }else{
                    this.merchant_voucher.push(v);
                }
                this.$emit('change_bill_payment' , {total_payment : this.summary.payment , discount : this.discount , merchant_voucher : this.merchant_voucher ,  voucher : this.voucher , shipment : this.shipment , couriers : this.couriers , order_status: this.order_status, insurances : this.logistic_insurance});
            }
        }
    }
</script>
<style scoped>
.warning-stock {
    color: #FF1D53;
}
</style>