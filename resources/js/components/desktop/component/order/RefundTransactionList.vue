<template>
   <div class="flex-basis-60" v-if="Object.keys(transaction).length > 0">
        <div class="box3-shadow-white">
            <h2 class="mbottom-15">Nomor Invoice : {{transaction.invoice_number}}</h2>
            <hr>
            <div class="transaction-history">
                <div class="transaction-status">
                    <h5 class="status refund">{{transaction.status_order.description}}</h5>
                    <div class="transaction-date mleft-10">
                        <h5>Transaksi Tanggal</h5>
                        <h5 class="mleft-5">{{transaction.created_at}}</h5>
                    </div>
                    <!-- <h6 class="btn-link">Lihat Detail</h6> -->
                </div>
                <!-- <div class="row-btn">
                    <div class="progress-transaction">
                        <ul class="progressbar" v-if="Object.keys(track).length > 0">
                            <li  :class="transaction.order_logs[t_index] != undefined ? 'active' : '' " v-for="(t_item , t_index) in track" :key="t_index">
                                <img src="/img/ic_checklist_purple.svg" alt="">
                            </li>
                        </ul>
                    </div>
                </div> -->
                <div class="info-refund">
                    <div class="row-100">
                        <h5>Invoice No : {{transaction.invoice_number}}</h5>
                        <h6 class="pointer purple mleft-10" @click="show_modal_detail()" >lihat invoice</h6>
                    </div>
                    <div class="row-100">
                        <h5>Status Refund : Pengajuan sudah dikonfirmasi</h5>
                        <img style="width: 15px;" class="mleft-10" src="/img/ic_checklist.svg" alt="">
                    </div>
                    <h5 class="mbottom-40">Opsi Refund : {{refund.refund_type}}</h5>
                    <div class="d-flex">
                        <div class="row-100 align-start mobile-column">
                            <div class="col-left">
                                <h6 class="black mtop-10">alasan refund</h6>
                            </div>
                            <div class="col-right">
                                <div class="refund-explanation">
                                    {{refund.description}}
                                </div>
                                <div class="preview-media">
                                    <div class="foto">
                                        <h6>foto</h6>
                                        <div class="d-flex">
                                            <div class="container-img">
                                                <img @click="show_preview_image('image' , refund.image_1)" :src="refund.image_1 != '' ? refund.image_1 : '/img/150x150.svg'" alt="">
                                            </div>
                                            <div class="container-img">
                                                <img @click="show_preview_image('image' , refund.image_2)" :src="refund.image_2 != '' ? refund.image_2 : '/img/150x150.svg'" alt="">
                                            </div>
                                            <div class="container-img">
                                                <img @click="show_preview_image('image' , refund.image_3)" :src="refund.image_3 != '' ? refund.image_3 : '/img/150x150.svg'" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <div class="preview-media">
                                    <div class="video">
                                        <h6>video</h6>
                                        <div class="d-flex">
                                            <div class="container-img">
                                                <video width="320" height="240" controls>
                                                    <source :src="refund.video" type="video/mp4">
                                                </video>
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                                <h5 class="mbottom-5" v-if="refund.refund_status_id > 1">alamat pengembalian barang</h5>
                                <div class="row-100 mbottom-5" v-if="refund.refund_status_id > 1">
                                    <div class="col-field">
                                        <h6>Nama Penerima</h6>
                                    </div>
                                    <div class="col-view">
                                        <h6>: {{transaction.vendor_id.pic}}</h6>
                                    </div>
                                </div>
                                <div class="row-100 mbottom-5" v-if="refund.refund_status_id > 1">
                                    <div class="col-field">
                                        <h6>No Telp</h6>
                                    </div>
                                    <div class="col-view">
                                        <h6>: {{transaction.vendor_id.phone}}</h6>
                                    </div>
                                </div>
                                <div class="row-100 mbottom-15" v-if="refund.refund_status_id > 1">
                                    <div class="col-field">
                                        <h6>Alamat</h6>
                                    </div>
                                    <div class="col-view">
                                        <h6>: {{transaction.vendor_id.address}} , {{transaction.vendor_id.city}} , {{transaction.vendor_id.province}}</h6>
                                    </div>
                                </div>
                                <div v-if="refund.refund_confirmation != null">
                                    <div class="d-flex align-center mbottom-5">
                                        <h5>Data pengiriman barang</h5>
                                        <h6 style="cursor:pointer" class="purple mleft-10" @click="show_refund_confirm_detail">ubah</h6>
                                    </div>
                                    <div class="row-100 mbottom-5" >
                                        <div class="col-field">
                                            <h6>Nama</h6>
                                        </div>
                                        <div class="col-view">
                                            <h6>: {{refund.refund_confirmation.consignor}}</h6>
                                        </div>
                                    </div>
                                    <div class="row-100 mbottom-5">
                                        <div class="col-field">
                                            <h6>Nomor Resi</h6>
                                        </div>
                                        <div class="col-view">
                                            <h6>: {{refund.refund_confirmation.receipt_number}}</h6>
                                        </div>
                                    </div>
                                    <div class="row-100 mbottom-15">
                                        <div class="col-field">
                                            <h6>Logistik yang digunakan</h6>
                                        </div>
                                        <div class="col-view">
                                            <h6>: {{refund.refund_confirmation.shipping_name}}</h6>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn-primary" @click="show_refund_confirm_detail" v-if="refund.status == 'confirm'">
                                    konfirmasi pengiriman barang
                                </button>

                                <h6 v-if="refund.status == 'vendor_approved' && refund.refund_type == 'cash'" style="cursor:pointer" class="purple mbottom-10" @click="show_refund_confirm_bill">masukkan rekening untuk pengembalian dana</h6>

                                <div v-if="refund.bank_account != null">
                                    <div class="d-flex align-center mbottom-15">
                                        <h5>rekening penerima refund</h5>
                                        <!-- <h6 style="cursor:pointer" class="purple mleft-10" @click="show_refund_confirm_bill">ubah</h6> -->
                                    </div>
                                    <div class="row-100 mbottom-5">
                                        <div class="col-field">
                                            <h6>atas nama penerima</h6>
                                        </div>
                                        <div class="col-view">
                                            <h6>: {{refund.bank_account.account_name}}</h6>
                                        </div>
                                    </div>
                                    <div class="row-100 mbottom-5">
                                        <div class="col-field">
                                            <h6>bank penerima</h6>
                                        </div>
                                        <div class="col-view">
                                            <h6>: {{refund.bank_account.bank_name}}</h6>
                                        </div>
                                    </div>
                                    <div class="row-100">
                                        <div class="col-field">
                                            <h6>no rekening penerima</h6>
                                        </div>
                                        <div class="col-view">
                                            <h6>: {{refund.bank_account.account_number}}</h6>
                                        </div>
                                    </div>
                                    <div class="row-100">
                                        <div class="col-field">
                                            <h6>KTP</h6>
                                        </div>
                                        <div class="col-view">
                                            <img style="height: 50%;width: 50%;" @click="show_preview_image('image' , refund.bank_account.identity_image)" :src="refund.bank_account.identity_image != '' ? refund.bank_account.identity_image : '/img/150x150.svg'" class="id-card">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrapper">
                    <ul class="stepprogress" v-if="refund.logs != null">
                        <li class="stepprogress-item is-done" v-for="(item , index) in refund.logs" :key="index">
                            <div class="tracking">
                                <div class="time">
                                    {{item.description.created_at}}
                                </div>
                                <div class="caption">
                                    <strong>{{item.created_at}}</strong>
                                    <span v-if="item.descriptions.description != ''">
                                        {{item.descriptions.description}}
                                    </span>

                                    <strong v-if="item.description != ''">Alasan : {{item.description}}</strong>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <modal-refund></modal-refund>
        <modal-detail-pesanan :invoice="invoice_response" :track="tracker" :logs="transaction.order_logs" :status_code="status_code"></modal-detail-pesanan>
        <modal-refund-confirmation :refund="refund.id" :invoice="transaction.invoice_number"></modal-refund-confirmation>
        <modal-refund-bank-account :refund="refund.id" :invoice="transaction.invoice_number"></modal-refund-bank-account>
        <modal-preview-image :data="data_image"></modal-preview-image>
    </div>
</template>

<script>
    import apiCustomer from '../../../../apis/Customer'
    import VueSlickCarousel from 'vue-slick-carousel'
    import 'vue-slick-carousel/dist/vue-slick-carousel.css'
    import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
    import ModalRefund from '../../modal/ModalRefund.vue'
    import ModalDetailPesanan from '../../modal/ModalDetailPesanan.vue'
    import ModalRefundConfirmation from '../../modal/ModalRefundConfirmation.vue'
    import ModalRefundBankAccount from '../../modal/ModalRefundBankAccount.vue'
    import ModalPreviewImage from '../../modal/ModalPreviewImage.vue'

    export default {
        name: "OrderTransactionList",
        data(){
            return {
                data_image : {type:'' , url_source :''},
                refund : {},
                transaction : {},
                tracker : {},
                invoice : '',
                invoice_response : {},
                status_code: null
            };
        },
        mounted(){
            this.refundbyInvoice();
            let invoice = decodeURI(this.$route.query.invoice)
            this.invoice = decodeURI(this.$route.query.invoice)
        },
        methods: {
            refundbyInvoice(){
                let invoice = decodeURI(this.$route.query.invoice);
                apiCustomer.refundbyInvoice({invoice : invoice}).then( response => {
                   if(response.data.code == 200){
                        this.transaction = response.data.data.transaction;
                        this.status_code = this.transaction.status_order.id;
                        this.refund = response.data.data.refund;
                        this.tracker = response.data.data.tracker;
                   }
                });
            },
            show_preview_image(type , url_source){
                this.data_image.type = type
                this.data_image.url_source = url_source
                $("#preview_image").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_refund_confirm_detail(){
                $("#refund_confirm_modal_detail").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_refund_confirm_bill(){
                $("#refund_confirm_modal_bill").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_detail(){
                apiCustomer.transactionByInvoiceNumber({invoice : this.invoice}).then( response => {
                    this.invoice_response = response.data.data
                    $("#detail_modal").fadeIn(function () {
                        $("#detail_modal").addClass('overflow-scroll')
                        $("body").addClass('overflow-hidden');
                    });
                });
                
            }
        },
        components : {
            VueSlickCarousel,
                ModalRefund,
                ModalDetailPesanan,
                ModalRefundConfirmation,
                ModalRefundBankAccount,
                ModalPreviewImage
        }
    }
</script>

<style scoped>

</style>
