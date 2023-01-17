<template>
    <div id="detail_modal" class="modal">
        <div class="modal-dialog detail-pesanan">
            <div class="modal-body">
                <span class="close-modal"></span>
                <div class="content-modal" v-if="Object.keys(invoice).length > 0">
                    <div class="form-detail">
                        <div class="title-detail">
                            <div class="status">
                                <h5 class="status-processing">{{invoice.description}}</h5>
                            </div>
                            <div class="date">
                                <h5>Transaksi Tanggal</h5>
                                <h5>{{invoice.transaction_date}}</h5>
                            </div>
                        </div>
                        <div class="progress-transaction">
                            <ul class="progressbar" v-if="Object.keys(track).length > 0">
                                <li :class="'active'">
                                    <img src="/img/ic_checklist_purple.svg" alt="">
                                </li>
                                <li :class="logs[t_index] != undefined ? 'active' : '' " v-for="(t_item , t_index) in track" :key="t_index">
                                    <img src="/img/ic_checklist_purple.svg" alt="">
                                </li>
                                <li class="cancel" v-if="status_code == 8 ||status_code == 9 || status_code == 10"></li>
                                <li class="refund" v-if="status_code == 6 ||status_code == 7"></li>
                            </ul>
                        </div>
                        <div class="col-invoice">
                            <h5 class="invoice">Invoice No : {{invoice.invoice_number}}</h5>
                            <!-- <a class="detail">lihat invoice</a> -->
                        </div>
                        <h5>{{invoice.description}} Dengan nomor Resi:</h5>
                        <div class="no-resi" v-if="invoice.awb_number != null">
                            <div class="resi">
                                <h5 >{{invoice.awb_number}}</h5>
                            </div>
                            <input class="copy-value d-none" v-on:focus="$event.target.select()" ref="clone" readonly :value="invoice.awb_number"/>
                            <img class="copy pointer" src="/img/ic_copy.svg" alt="" @click="copy">
                            <img class="copied hide" src="/img/ic_checklist.svg" alt="">
                        </div>
                        <div class="no-resi" v-else>
                            <div class="resi">
                                <h5 >{Resi Number}</h5>
                            </div>
                        </div>
                        <div class="card-summary" v-if="invoice.productswithdetail.length > 0" v-for="(item , index) in invoice.productswithdetail" :key="index">
                            <div class="card-cart">
                                <div class="content-img">
                                    <img :src="item.product_detail_with_product.link" alt="">  
                                </div> 
                                <div class="detail-product-summary">
                                    <div class="col-6">
                                        <h6>{{item.product_detail_with_product.name}}</h6>
                                        <h6>{{item.quantity}} x {{item.fix_price | RupiahFormat}}</h6>
                                        <h6 v-if="item.product_detail_with_product.variant_value_2 != '' || item.product_detail_with_product.variant_value_2 != null && item.product_detail_with_product.variant_value_1 != ''">Variant : {{ item.product_detail_with_product.variant_value_1 }} {{ item.product_detail_with_product.variant_value_2 }}</h6>
                                        <h6 v-if="item.notes !== null">Catatan : {{ item.notes }}</h6>
                                    </div>
                                    <div class="col-6 text-right">
                                        <h6>Total harga</h6>
                                        <h6>{{(parseInt(item.quantity) * item.fix_price) | RupiahFormat}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5 class>info pengiriman</h5>
                        <div class="row-info mtop-10">
                            <div class="col-field">
                                <h6>Nama Penerima</h6>
                            </div>
                            <div class="col-view">
                                <h6>{{ JSON.parse(invoice.consignee).name }}</h6>
                            </div>
                        </div>
                        <div class="row-info">
                            <div class="col-field">
                                <h6>telepon penerima</h6>
                            </div>
                            <div class="col-view">
                                <h6>{{ JSON.parse(invoice.consignee).phone_number }}</h6>
                            </div>
                        </div>
                        <div class="row-info">
                            <div class="col-field">
                                <h6>alamat penerima</h6>
                                <!-- <div class="tag">
                                    <h6>Tag : <strong>Rumah</strong></h6>
                                </div> -->
                            </div>
                            <div class="col-view">
                                <h6 class="w-80">
                                    {{ JSON.parse(invoice.consignee).address }}
                                </h6>
                            </div>
                        </div>
                        <div class="row-info">
                            <div class="col-field">
                                <h6>kurir</h6>
                            </div>
                            <div class="col-view">
                                <h6>{{ JSON.parse(invoice.rate).logistic.name + ' , ' + JSON.parse(invoice.rate).rate.name }}</h6>
                            </div>
                        </div>
                        <h5 class="mtop-20">rincian pembayaran</h5>
                        <div class="row-info mtop-10">
                            <div class="col-field">
                                <h6>metode pembayaran</h6>
                            </div>
                            <div class="col-view">
                                <h6 class="uppercase">({{invoice.channel}}) - {{invoice.payment_channel}}</h6>
                            </div>
                        </div>
                        <div class="row-info">
                            <div class="col-field">
                                <h6>total belanja</h6>
                            </div>
                            <div class="col-view">
                                <h6>{{invoice.invoice_total_payment | RupiahFormat}}</h6>
                            </div>
                        </div>
                        <div class="row-info">
                            <div class="col-field">
                                <h6>total ongkir</h6>
                            </div>
                            <div class="col-view">
                                <h6>{{invoice.shipping_cost | RupiahFormat}}</h6>
                            </div>
                        </div>
                         <div class="row-info">
                            <div class="col-field">
                                <h6>Asuransi pengiriman</h6>
                            </div>
                            <div class="col-view">
                                <h6>{{invoice.insurance_fee | RupiahFormat}}</h6>
                            </div>
                        </div>
                        <div class="row-info">
                            <div class="col-field">
                                <h6>Voucher</h6>
                            </div>
                            <div class="col-view">
                                <h6> - {{invoice.total_discount | RupiahFormat}}</h6>
                            </div>
                        </div>
                        <div class="row-info">
                            <div class="col-field">
                                <h6>Penggunaan Poin</h6>
                            </div>
                            <div class="col-view">
                                <h6> - {{parseInt(invoice.point) | RupiahFormat}}</h6>
                            </div>
                        </div>
                        <div class="row-info">
                            <div class="col-field">
                                <h6>total dibayarkan</h6>
                            </div>
                            <div class="col-view">
                                <h6>{{((parseInt(invoice.shipping_cost) + parseInt(invoice.invoice_total_payment) + parseInt(invoice.insurance_fee) ) - parseInt(invoice.point)) - parseInt(invoice.total_discount) | RupiahFormat}}</h6>
                            </div>
                        </div>
                        <hr>
                        <div class="wrapper">
                            <ul class="stepprogress"  v-if="Object.keys(invoice.tracking).length > 0">
                                <li class="stepprogress-item is-done" v-for="(tracking , trindex) in invoice.tracking" :key="trindex">
                                    <div class="tracking">
                                        <div class="time">
                                            {{tracking.time}}
                                        </div>
                                        <div class="caption">
                                            <strong>{{tracking.date}}</strong>
                                            {{tracking.description}}<br>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="content-modal">
                        <!-- <large-skeleton :count="1"></large-skeleton> -->
                        <list-skeleton :count="3"></list-skeleton>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LargeSkeleton from '../../skeleton/LargeSkeleton.vue'
    import ListSkeleton from '../../skeleton/ListSkeleton.vue'
    import CloseModal from '../component/CloseModal.vue'
    export default {
        name: "ModalDetailPesanan.vue",
        data() {
            return {
            }
        },
        props :['invoice','track' ,'logs', 'status_code'],
        components:{
                CloseModal,
                LargeSkeleton,
                ListSkeleton
        },
        mounted(){
        },
        methods : {
            ino(){
             
            },
            copy() {
                $('.copy-value').show()
                this.$refs.clone.focus();
                document.execCommand('copy');
                $('.copy-value').hide()
                $('.copy').addClass('hide');
                $('.copied').removeClass('hide');

                setTimeout(
                    function(){
                        $('.copied').addClass('hide');
                        $('.copy').removeClass('hide');
                    },3000
                ); 
            }
        }
    }
</script>

<style>

</style>