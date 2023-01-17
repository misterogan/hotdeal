<template>
   <div class="flex-basis-60 max-width-60">
        <div class="box3-shadow-white">
            <div v-if="is_loading">
                <div class="ph-row mtop-20 mbottom-35">
                    <div class="ph-col-3 bg-placeholder"></div>
                </div>
                <div class="ph-row mbottom-15">
                    <div class="ph-col-5 bg-placeholder"></div>
                </div>
                <div class="ph-row mbottom-20">
                    <div class="ph-col-4 bg-placeholder mright-10"></div>
                    <div class="ph-col-4 bg-placeholder"></div>
                </div>
                <div class="ph-row mbottom-20">
                    <div class="ph-col-7 bg-placeholder"></div>
                </div>
                <div class="ph-row mbottom-20">
                    <div class="ph-col-3 bg-placeholder"></div>
                </div>
                <div class="ph-row justify-between">
                    <div class="ph-col-6 ph-h75 bg-placeholder mright-10"></div>
                    <div class="ph-col-6 ph-h75 bg-placeholder"></div>
                </div>
            </div>
            <div v-if="!is_loading">
                <h2 class="mbottom-30">Menunggu Pembayaran</h2>
                <h5 class="fw-700 mbottom-15 bgcolor-pink" v-if="Object.keys(this.transaction).length > 0">Ada {{Object.keys(this.transaction).length}} Pesanan yang belum di bayar</h5>
                <div v-if="Object.keys(transaction).length > 0">
                    <div class="transaction-history mbottom-15" v-for="(item, index) in transaction" :key="index">
                        <div class="transaction-status">
                            <h5 class="status waiting mright-10" >{{item.status.description}}</h5>
                            <div class="transaction-date">
                                <h5 class="fw-700 mright-5">Transaksi Tanggal</h5>
                                <h5 class="fw-700">{{item.order_date}}</h5>
                            </div>
                        </div>
                        <div v-if="item.order_details != null">
                            <div class="list-transaksi">
                                <div class="col-6">
                                    <h5 class="fw-700">Metode Pembayaran</h5>
                                    <h5>{{item.order_payments.channel_payment.payment_channel}}</h5>
                                    <div class="d-flex align-center account" v-if="item.status.status_code == 'awaiting_payment' && item.order_payments.channel_payment.channel != 'E-WALLET'">
                                        <h5 class="bgcolor-purple fw-700 mtop-10 mbottom-10">
                                            {{item.order_payments.account_number}}
                                        </h5>
                                        <img class="copy pointer mleft-10" v-bind:class="'copy-'+index" :attr="index" src="/img/ic_copy.svg" @click="copyTheText(item.order_payments.account_number,'account' , index)" width="20">
                                        <img class="copied hide mleft-10" v-bind:class="'copied-'+index" :attr="index" key="" src="/img/ic_checklist.svg" alt="" width="20">
                                    </div>
                                </div>
                                <div class="col-6 product-invoice amount">
                                    <ul>
                                        <li><h5 class="fw-700">Jumlah yang harus dibayar</h5></li>
                                        <li class="d-flex align-center mtop-10">
                                            <h5 class="bgcolor-purple fw-700">{{item.order_payments.mount | RupiahFormat}}</h5>
                                            <img class="copy pointer mleft-10" v-bind:class="'copy-'+index" :attr="index" src="/img/ic_copy.svg" @click="copyTheText(item.order_payments.mount,'amount' , index)" width="20">
                                            <img class="copied hide mleft-10 " v-bind:class="'copied-'+index" :attr="index" key="" src="/img/ic_checklist.svg" alt="" width="20">
                                        </li>
                                        <!-- <li class="detail mtop-10 width-max" @click="sho">detail pesanan</li> -->
                                    </ul>
                                </div>
                            </div>
                            <h5 class="invoice fp-pink fw-500 fs-12 mbottom-5">Lakukan pembayaran sebelum : {{item.order_payments.expiration_date}}</h5>
                        </div>
                        <div class="row-btn" v-if="item.status.status_code == 'awaiting_payment'" >
                            <button v-if="item.status.status_code == 'awaiting_payment' && item.order_payments.channel_payment.channel == 'E-WALLET'" class="btn-primary" href="javascript:void(0)"  @click="continuePayment(item.transaction_number)">
                                <img style="width:18px" src="/img/ic_purse.svg" alt="">
                                lanjutkan Pembayaran
                            </button>
                            <!-- <div class="total">
                                <h5>Total Bayar</h5>
                                <div class="col-harga-produk">
                                    <h5 class="price">{{item.total_payment | RupiahFormat}}</h5>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div v-else>
                    <blank-page :message="'Belum Ada Pesanan'" :image="'/img/animation_empty_order.svg'"></blank-page>
                </div>
            </div>
        </div>
        <input type="text" style="display:none;widht:10px" ref="textforcopy" id="text-data-for-copy"   />
        <modal-checkout-pending-transaction :data="modal_data" :reload="'false'"></modal-checkout-pending-transaction>
        <modal></modal>
    </div>
</template>

<script>
    import Rupiah from '../../../../utils/Global'
    import apiCustomer from '../../../../apis/Customer'
    import VueSlickCarousel from 'vue-slick-carousel'
    import 'vue-slick-carousel/dist/vue-slick-carousel.css'
    import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
    import Modal from '../../Modal.vue'
    import ModalCheckoutPendingTransaction from '../../modal/ModalCheckoutPendingTransaction.vue'
    import BlankPage from '../../BlankPage.vue'

    export default {
        name: "OrderTransactionList",
        data(){
            return {
                transaction : {},
                modal_data : {},
                is_loading: true,
                paginate : {
                    page : 1,
                    current_page : 1,
                    total : 1,
                    filter : 'all'
                }
            };
        },
        computed: {

        },
        mounted(){
           this.orderList();
        },
        methods: {
            copyTheText(val,f, index) {
                if(isNaN(val)){
                    return alert('Error to copy ')
                }
                $('#text-data-for-copy').val(val)
                var copyText = document.getElementById("text-data-for-copy");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(copyText.value);
                
                $('.'+f).find('.copy-'+index).addClass('hide');
                $('.'+f).find('.copied-'+index).removeClass('hide');

                setTimeout(
                    function(){
                         $('.'+f).find('.copied-'+index).addClass('hide');
                         $('.'+f).find('.copy-'+index).removeClass('hide');
                    },3000
                );  
            },
            orderList(){
                apiCustomer.pendingTransaction(this.paginate).then( response => {
                   if(response.data.code === 200){
                       this.transaction = response.data.data.orders
                       this.is_loading = false
                       this.$emit('count_transaction' , Object.keys(this.transaction).length )
                   }
                });
            },
            filterTransaction(status){
                apiCustomer.pendingTransaction({status_id : status}).then( response => {
                   if(response.data.code === 200){
                       this.transaction = response.data.data.orders
                   }
                });
            },
            continuePayment(transaction_number){
                apiCustomer.continuePayment({transaction_id : transaction_number}).then( response => {
                    if(response.data.code == 200){
                        window.location.href = response.data.data;
                    }
                });
            },
            show_modal_wait(id){
                apiCustomer.detailPendingOrder({transaction_id : id}).then( response => {
                    if(response.data.code == 200){
                        this.modal_data = response.data.data
                        $("#waiting_payment_modal").fadeIn(function () {
                            $("#waiting_payment_modal").addClass('overflow-scroll');
                        });
                    }
                });
            },
            show_modal_detail(){
                $("#detail_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            }
        },
        components : {
            VueSlickCarousel,
            Modal,
            ModalCheckoutPendingTransaction,
            BlankPage
        }
    }
</script>

<style scoped>

</style>
