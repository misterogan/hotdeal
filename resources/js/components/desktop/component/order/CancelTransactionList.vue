<template>
   <div class="flex-basis-60 max-width-60">
        <div class="box3-shadow-white" v-if="Object.keys(data).length > 0 ">
            <h2 class="mbottom-15">Nomor Invoice : {{data.invoice}}</h2>
            <hr>
            <div class="transaction-history">
                <div class="transaction-status">
                    <h5 class="status refund">{{data.status.description}}</h5>
                    <div class="transaction-date mleft-10">
                        <h5>Transaksi Tanggal</h5>
                        <h5>&nbsp; {{data.created_at}}</h5>
                    </div>
                </div>
                <div class="info-refund">
                    <h5>Invoice No : {{data.invoice}}</h5>
                    <h5>Status : {{data.status.description}}</h5>
                    <h5>Alasan pembatalan : {{data.last_status.description}}</h5>
                    <h5 v-if="data.last_status.status == 9">Opsi pengembalian dana : 
                        <span v-if="data.refund.refund_type != null">{{data.refund.refund_type}}</span> 
                        <span class="pointer" v-else style="color: #9494CB;" @click="showModalAccount">pilih opsi pengembalian dana</span>
                    </h5>
                    <div class="card-summary" v-if="Object.keys(data.product).length > 0" v-for="(item , index) in data.product" :key="index">
                        <div class="card-img">
                            <img :src="item.product_detail_with_product.link" alt="">
                        </div>
                        <div class="card-cart">
                            <div class="detail-product-summary">
                                <div class="col-6"> 
                                    <h6>{{item.product_detail_with_product.name}} {{item.quantity}} x {{item.fix_price | RupiahFormat}}</h6>
                                </div>
                                <hr class="mright-5 mleft-5">
                                <div class="col-6 mleft-20">
                                    <h6>Total harga</h6>
                                    <h6>{{(item.fix_price * item.quantity) | RupiahFormat}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-100">
                        <div class="col-right">
                            <h5 class="mbottom-15">info pengiriman :</h5>
                            <div class="row-100 mbottom-15">
                                <div class="col-field">
                                    <h6>Nama Penerima</h6>
                                </div>
                                <div class="col-view">
                                    <h6>{{ data.shipping.consignee | JSONSTRINGIFY('name') }}</h6>
                                </div>
                            </div>
                            <div class="row-100 mbottom-15">
                                <div class="col-field">
                                    <h6>telepon penerima</h6>
                                </div>
                                <div class="col-view">
                                    <h6>{{ data.shipping.consignee | JSONSTRINGIFY('phone_number') }}</h6>
                                </div>
                            </div>
                            <div class="row-100 mbottom-15">
                                <div class="col-field">
                                    <h6>Alamat penerima</h6>
                                </div>
                                <div class="col-view">
                                    <h6>{{ data.shipping.consignee | JSONSTRINGIFY('address') }}</h6>
                                </div>
                            </div>
                            <div class="row-100 mbottom-25">
                                <div class="col-field">
                                    <h6>Kurir</h6>
                                </div>
                                <div class="col-view">
                                    <h6>{{ data.shipping.rate | JSONSTRINGIFY_LOGISTIC('name') }}</h6>
                                </div>
                            </div>
                            <h5 class="mbottom-15">rincian pembayaran :</h5>
                            <div class="row-100 mbottom-15" >
                                <div class="col-field">
                                    <h6>metode pembayaran</h6>
                                </div>
                                <div class="col-view">
                                    <h6 class="uppercase">{{data.payment.channel}} - {{data.payment.label}}</h6>
                                </div>
                            </div>
                            <div class="row-100 mbottom-15">
                                <div class="col-field">
                                    <h6>total belanja</h6>
                                </div>
                                <div class="col-view">
                                    <h6>{{data.total_payment | RupiahFormat}}</h6>
                                </div>
                            </div>
                            <div class="row-100 mbottom-15">
                                <div class="col-field">
                                    <h6>total ongkir</h6>
                                </div>
                                <div class="col-view">
                                    <h6>{{data.shipping.shipping_cost | RupiahFormat}}</h6>
                                </div>
                            </div>
                            <!-- <div class="row-100 mbottom-15">
                                <div class="col-field">
                                    <h6>asuransi</h6>
                                </div>
                                <div class="col-view">
                                    <h6>Rp 9.000</h6>
                                </div>
                            </div> -->
                            <div class="row-100 mbottom-10 border-bottom-grey">
                                <div class="col-field">
                                    <h6>total dibayarkan</h6>
                                </div>
                                <div class="col-view">
                                    <h6>{{(parseInt(data.total_payment) + parseInt(data.shipping.shipping_cost) ) | RupiahFormat}}</h6>
                                </div>
                            </div>
                            <h6 v-if="data.refund.bank_account == null && data.refund.refund_type == 'cash'" class="pointer" style="color: #9494CB;" @click="showModalAccount">pilih pengembalian dana</h6>
                            <h5 class="mbottom-15" v-if="data.refund.bank_account != null">rekening pengembalian dana</h5>
                            <div v-if="data.refund.bank_account != null && data.refund.refund_type == 'cash' " >
                                <div class="row-100 mbottom-15" >
                                    <div class="col-field">
                                        <h6>atas nama penerima</h6>
                                    </div>
                                    <div class="col-view">
                                        <h6>{{data.refund.bank_account.account_name}}</h6>
                                    </div>
                                </div>
                                <div class="row-100 mbottom-15">
                                    <div class="col-field">
                                        <h6>bank penerima</h6>
                                    </div>
                                    <div class="col-view">
                                        <h6>{{data.refund.bank_account.bank_name}}</h6>
                                    </div>
                                </div>
                                <div class="row-100">
                                    <div class="col-field">
                                        <h6>no. rekening penerima</h6>
                                    </div>
                                    <div class="col-view">
                                        <h6>{{data.refund.bank_account.account_number}}</h6>
                                    </div>
                                </div>
                                <div class="row-100">
                                    <div class="col-field">
                                        <h6>KTP</h6>
                                    </div>
                                    <div class="col-view">
                                        <img :src="data.refund.bank_account.identity_image" class="id-card">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrapper">
                <ul class="stepprogress" v-if="data.refund != null">
                    <li class="stepprogress-item is-done" v-if="Object.keys(data.refund.logs).length > 0" v-for="(item, index) in data.refund.logs" :key="index">
                        <div class="tracking">
                            <div class="time">
                                <!-- {{item.created_at}} -->
                            </div>
                            <div class="caption">
                                <strong>{{item.created_at}}</strong>
                                {{item.description}}
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <modal-refund-cancel-order :invoice="data"></modal-refund-cancel-order>
    </div>
</template>

<script>

    import apiCustomer from '../../../../apis/Customer'
    import ModalRefundCancelOrder from '../../modal/ModalRefundCancelOrder.vue'
    export default {
        name: "CancelTransactionList",
        data(){
            return {
                data : {}
            }
        },
        mounted(){
            this.cancelOrder(this.keyword = this.$route.query.invoice)
        },
        methods : {
            cancelOrder(invoice){
                apiCustomer.cancelOrderbyInvoice({invoice:invoice}).then(response => {
                    this.data = response.data.data
                })
            },
            showModalAccount(){
                $("#refund_cancel_order").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            }            
        },
        components : {
            ModalRefundCancelOrder
        }
    }
</script>

<style scoped>

</style>
