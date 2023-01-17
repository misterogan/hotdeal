<template>
    <div>
        <div class="main-content">
            <div class="center-menu product" style="width:100%">
                <div class="main-menu">
                    <div class="box refund-list" v-if="Object.keys(detail_refunds).length > 0">
                        <h3 class="mbottom-10">detail refund</h3>
                        <div class="transaksi-refund">
                            <div class="info-refund">
                                <div class="tgl-transaksi">
                                <h5 class="status-refund">{{detail_refunds.transaction.status_order.description}}</h5>
                                    <div class="date">
                                        <h5>Transaksi Tanggal :</h5>
                                        <h5>{{ detail_refunds.transaction.created_at | DateFormat}} </h5>
                                    </div>
                                </div>
                                <div class="row-100">
                                    <div class="col-field">
                                        <h5>Invoice No</h5>
                                    </div>
                                    <div class="col-view">
                                        <h5>: {{detail_refunds.transaction.invoice_number}}</h5>
                                        <h6 class="pointer purple mleft-10" @click="show_modal_detail" >lihat invoice</h6>
                                    </div>
                                </div>
                                <div class="row-100">
                                    <div class="col-field">
                                        <h5>Status Refund</h5>
                                    </div>
                                    <div class="col-view">
                                        <h5>: {{detail_refunds.status_description}}</h5>
                                        <img style="width: 15px;" class="mleft-10" src="/img/ic_checklist.svg" alt="">
                                    </div>
                                </div>
                                <div class="row-100">
                                    <div class="col-field">
                                        <h5>Opsi Refund</h5>
                                    </div>
                                    <div class="col-view">
                                        <h5>: {{detail_refunds.refund_type}}</h5>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="row-100">
                                        <div class="col-left">
                                            <h6 class="black">alasan refund</h6>
                                        </div>
                                        <div class="col-right">
                                            <div class="refund-explanation">
                                                {{detail_refunds.description}}
                                            </div>
                                            <div class="preview-media">
                                                <div class="foto">
                                                    <h6>foto</h6>
                                                    <div class="d-flex">
                                                        <div class="container-img" v-if="detail_refunds.image_1">
                                                            <img :src="detail_refunds.image_1" alt="" @click="modal_preview({type:'image' , url_source : detail_refunds.image_1})">
                                                        </div>
                                                        <div class="container-img" v-if="detail_refunds.image_2">
                                                            <img :src="detail_refunds.image_2"  alt=""  @click="modal_preview({type:'image' , url_source : detail_refunds.image_2})">
                                                        </div>
                                                        <div class="container-img" v-if="detail_refunds.image_3">
                                                            <img :src="detail_refunds.image_3"  alt=""  @click="modal_preview({type:'image' , url_source : detail_refunds.image_3})">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="preview-media" v-if="detail_refunds.video">
                                                <div class="video">
                                                    <h6>video</h6>
                                                    <div class="d-flex">
                                                        <div class="container-img">
                                                            <video width="320" height="240" controls>
                                                                <source :src="detail_refunds.video" type="video/mp4">
                                                            </video>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row-btn" v-if="detail_refunds.refund_status_id == 1">
                                                <button class="btn-secondary" @click="actions('reject')">
                                                    tolak
                                                </button>
                                                <button class="btn-primary" @click="actions('approve')">
                                                    terima
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <br>
                            <div  v-if="detail_refunds.refund_confirmation != null">
                                 <div class="info-refund">
                                     <div class="row-100">
                                        <div class="col-field">
                                            <h5>Data Pengiriman Barang</h5>
                                        </div>
                                    </div>
                                    <div class="row-100">
                                        <div class="col-field">
                                            <h5>Nama</h5>
                                        </div>
                                        <div class="col-view">
                                            <h5>: {{detail_refunds.refund_confirmation.consignor}}</h5>
                                        </div>
                                    </div>
                                    <div class="row-100">
                                        <div class="col-field">
                                            <h5>Nomor Resi</h5>
                                        </div>
                                        <div class="col-view">
                                            <h5>: {{detail_refunds.refund_confirmation.receipt_number}}</h5>
                                        </div>
                                    </div>
                                    <div class="row-100">
                                        <div class="col-field">
                                            <h5>Logistik yang digunakan</h5>
                                        </div>
                                        <div class="col-view">
                                            <h5>: {{detail_refunds.refund_confirmation.shipping_name}}</h5>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            
                            <div class="konfirmasi-admin" v-if="detail_refunds.refund_status_id == 9">
                                <h6>disclaimer konfirmasi admin :</h6>
                                <h6>Dengan menekan tombol konfirmasi anda setuju bahwa admin hotdeal akan meneruskan proses refund. Pastikan barang pengembalian sudah anda terima dari user.</h6>
                                <button @click="show_refund_confirm_admin" class="btn-primary">konfirmasi ke admin</button>  
                            </div>
                            <div class="wrapper">
                                <ul class="stepprogress" v-if="Object.keys(detail_refunds.logs).length">
                                    <li class="stepprogress-item" v-bind:class="index == 0 ? 'is-done' : ''" v-for="(item , index) in detail_refunds.logs" :key="index">
                                        <div class="tracking">
                                            <div class="time">
                                                {{ formatHours(item.created_at) }}
                                            </div>
                                            <div class="caption">
                                                <strong>{{item.created_at}}</strong>
                                                {{item.descriptions.description_vendor != '' ? item.descriptions.description_vendor : ''}} <br>
                                                
                                                <strong v-if="item.description != ''">Alasan : {{item.description}}</strong>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- <li class="stepprogress-item">
                                        <div class="tracking">
                                            <div class="time">
                                                08.29
                                            </div>
                                            <div class="caption">
                                                <strong>Seller - Rabu, 3 Nov 2021</strong>
                                                Transaksi Dikonfirmasi <br>
                                                Transaksi Telah Dikonfirmasi Pembeli Dan Menunggu Review Hotdeal <br>
                                            </div>
                                        </div>
                                    </li> -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal-preview-image :data="source_data"></modal-preview-image>
        <modal-refund-vendor :action="action" :invoice="invoice"></modal-refund-vendor>
        <modal-detail-pesanan :invoice="invoice_response" :track="tracker" :logs="order_logs" :status_code="status_code"></modal-detail-pesanan>
    </div>
</template>

<script>
    import ModalRefundVendor from './modal/ModalRefundVendor.vue'
    import apiVendor from '../../apis/Vendor'
    import ModalDetailPesanan from '../desktop/modal/ModalDetailPesanan.vue'
    import Modal from '../desktop/Modal.vue'
    import ModalPreviewImage from '../desktop/modal/ModalPreviewImage.vue'

    export default {
        data(){
            return {
                detail_refunds : {},
                invoice : '',
                action : '',
                invoice_response : {},
                tracker : {},
                order_logs : {},
                source_data : {type:'image' , url_source : ''},
                status_code: null
            }
        }, 
        components : {
            ModalRefundVendor,
            ModalDetailPesanan,
            Modal,
            ModalPreviewImage
            },
        mounted (){
            let params = decodeURI(this.$route.query.invoice)
            this.invoice = params;
            this.getDetailRefund()
        },
        methods: {
            getDetailRefund(){
                apiVendor.getDetailRefundByInvoice({invoice : this.invoice}).then( response => {
                    this.detail_refunds = response.data.data;
                    this.status_code = response.data.data.transaction.status;
                    this.detail_refunds.invoice_number = this.invoice;
                    $("#detail_refund").fadeIn(function () {
                        $("#detail_refund").addClass('overflow-scroll')
                        $("body").addClass('overflow-hidden');
                    });
                });
            },
            actions(actions){
                this.action = actions
                $("#refund_explanation").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_refund_confirm_admin(){
                $("#refund_confirm_admin").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_detail(){
                apiVendor.transactionByInvoiceNumber({invoice : this.invoice}).then( response => {
                    this.invoice_response = response.data.data
                    this.tracker = response.data.data.tracker
                    this.order_logs = response.data.data.order_log_step
                    $("#detail_modal").fadeIn(function () {
                        $("#detail_modal").addClass('overflow-scroll')
                        $("body").addClass('overflow-hidden');
                    });
                });
                
            },
            modal_preview(image){
                this.source_data = image
                $("#preview_image").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            formatHours(date){
                if (date){
                    return date.substr(-8, 5)
                }
            },
        },
    }
</script>
