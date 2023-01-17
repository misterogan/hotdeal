<template>
    <div>
        <div class="main-content">
            <div class="center-menu product" style="width:100%">
                <div class="main-menu">
                    <div class="box product-list">
                        <h3>daftar refund</h3>
                        <div class="row-100 second-top">
                            <div class="col search">
                                <div class="container-search">
                                    <form v-on:submit.prevent="refundOrder(pagination.page = 1)">
                                        <input @keyup="refundOrder(pagination.page = 1)" v-model="pagination.search" name="search" class="input-search" type="search" id="search" autocomplete="off" value="" placeholder="Masukan Nomor Invoice" />
                                        <img class="ic_search" src="/img/ic_search.svg" alt="">
                                    </form>
                                </div>
                            </div>
                            <div class="col select date">
                                    <h5>tanggal pesanan</h5>
                                    <input v-model="pagination.date" type="date" name="daterange" value="11/01/2021 - 11/15/2021" @change="refundOrder(pagination.page = 1)" />
                                </div>
                            <filter-master-status-order  @updatePage="updatePage($event)"></filter-master-status-order>
                        </div>
                        <div class="row-100">
                            <div class="order-product">
                                <div class="head-data">
                                    <ul>
                                        <li style="width:20%">detail pembelian</li>
                                        <li style="width:25%">produk</li>
                                        <li style="width:15%">total transaksi</li>
                                        <li style="width:10%">status</li>
                                        <li style="width:15%">jasa pengiriman</li>
                                        <li style="width:15%"></li>
                                    </ul>
                                </div>
                                <div class="body-data">
                                    <div v-if="Object.keys(list).length > 0">
                                        <div class="row-data" v-for="(item , index) in list" :key="index">
                                            <div class="col" style="width: 20%">
                                                <ul>
                                                    <li>{{item.shipping.consigner | JSONSTRINGIFY('name')}}</li>
                                                    <li class="fw-300">tanggal pesanan</li>
                                                    <li class="fw-300">{{item.created_at}}</li>
                                                    <li>{{item.invoice_number}}</li>
                                                </ul>
                                            </div>
                                            <div class="col" style="width:25%">
                                                <div class="d-flex align-center" v-if="item.products != null" v-for="(product , iproduct) in item.products" :key="iproduct">
                                                    <div class="container-img">
                                                        <img :src="product.product_info.image.link" alt="">
                                                    </div>
                                                    <ul>
                                                        <li class="product-name">{{product.product_info.name}}</li>
                                                        <li class="fw-300">SKU : {{product.product_info.sku}}</li>
                                                        <li>x {{product.quantity}}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col" style="width:15%">
                                                <ul>
                                                    <li>{{item.invoice_total_payment | RupiahFormat}}</li>
                                                    <li class="fw-300">{{item.order_payment.bank_code}}</li>
                                                </ul>
                                            </div>
                                            <div class="col" style="width:10%">
                                                <div class="w-80">
                                                    <h6>{{item.status_order.description}}</h6>
                                                </div>
                                            </div>
                                            <div class="col" style="width:15%">
                                                <ul>
                                                    <li>{{ item.shipping.rate | JSONSTRINGIFY_LOGISTIC('name') }}</li>
                                                    <li>pengiriman</li>
                                                    <li v-if="item.estimasi != null">{{ item.estimasi }} hari</li>
                                                </ul>
                                            </div>
                                            <div class="col" style="width:15%">
                                                <div>
                                                    <!-- <h6 style="cursor:pointer; color: #9494CB;" @click="show_modal_detail">rincian pemesanan</h6> -->
                                                    <button v-if="item.refund.refund_status_id == 9" @click="approve_refund(item.invoice_number , index)">Setujui</button>
                                                    <button v-if="item.refund.refund_status_id == 1" href="javascript:void(0)" @click="detail_refund(item.invoice_number , index)">Proses</button>
                                                    <!-- <button v-if="item.status_order.id == 2" @click="updateStatusInvoice(item.invoice_number , 'processed')">proses</button> -->
                                                    <router-link class="btn-link" :to="'/vendor/order/list/refund/detail?invoice='+item.invoice_number">
                                                        <button href="javascript:void(0)">detail refund</button>
                                                    </router-link>
                                                    <!-- <button href="javascript:void(0)" @click="detail_refund(item.invoice_number , index)">detail refund</button> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mtop-30" v-else>
                                        <blank-page :message="'Belum Ada Daftar Produk'" :image="'/img/animation_empty_wishlist.svg'"></blank-page>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-100 mtop-50">
                            <pagination :pagination="pagination" @updatePage="updatePage($event)"></pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal-detail-refund :refund="detail_refunds" ></modal-detail-refund>
        <modal-approve-refund :invoice="invoice" ></modal-approve-refund>
    </div>
</template>

<script>
import apiCustomer from '../../apis/Customer'
import apiVendor from '../../apis/Vendor'
import ModalDetailPesanan from '../desktop/modal/ModalDetailPesanan.vue'
import ModalDetailRefund from './modal/ModalDetailRefund.vue'
import ModalApproveRefund from './modal/ModalApproveRefund.vue'
import FilterMasterStatusOrder from '../pagination/FilterMasterStatusOrder.vue'
import Pagination from '../pagination/pagination.vue'
import BlankPage from '../desktop/BlankPage.vue'

export default {
    data(){
        return {
            list : {},
            track: {},
            selected_logs : {},
            invoice_response : {},
            invoice : '',
            detail_refunds : {},
            is_loading : true,
            pagination : {
                page : 1,
                current : 1,
                total : 1,
                status : '',
                date : '',
                search : '',
                filter : '',
                perpage : 10
            }
        }
    },
    mounted(){
        if(this.$route.query.filter != undefined){
            this.pagination.filter = this.$route.query.filter
        }
        this.refundOrder()
    },
    methods : {
        refundOrder(){
            this.timer = setTimeout(() => {
                apiVendor.refundOrder(this.pagination).then( response =>{
                    this.list = response.data.data.list
                    this.pagination.current = response.data.data.current_page
                    this.pagination.total = response.data.data.total
                    this.track = response.data.data.tracker
                    this.is_loading = false
                });
            }, 500);
        },
        updateStatusInvoice(invoice_number , status_order){
            apiVendor.updateStatusInvoice({invoice_number : invoice_number , status_code : status_order}).then( response =>{
                if(response.status == 200){
                    location.reload()
                }
            });
        },
        updatePage(e){
            if(e.type == 'page'){
                this.pagination.page = e.value
                if(this.pagination.total < e.value){
                    return ;
                }
            }
            if(e.type == 'filter'){
                this.pagination.filter = e.value
                this.pagination.page = 1
            }
            this.refundOrder()
        },
        detail_refund(invoice , index){
            this.detail_refunds = {}
            apiVendor.getDetailRefundByInvoice({invoice : invoice}).then( response => {
                this.detail_refunds = response.data.data;
                this.detail_refunds.invoice_number = invoice;
                $("#detail_refund").fadeIn(function () {
                    $("#detail_refund").addClass('overflow-scroll')
                    $("body").addClass('overflow-hidden');
                });
            });
        },
        approve_refund(invoice , index){
            this.invoice = invoice;
            $("#approve_refund").fadeIn(function () {
                $("#detail_refund").addClass('overflow-scroll')
                $("body").addClass('overflow-hidden');
            });
        }
    },
    components : {
            ModalDetailPesanan,
            ModalDetailRefund,
            ModalApproveRefund,
            FilterMasterStatusOrder,
            BlankPage,
            Pagination
    }
}
</script>
