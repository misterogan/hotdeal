<template>
    <div>
        <div class="main-content">
            <div class="center-menu product" style="width:100%">
                <div class="main-menu">
                    <div class="row-100">
                        <div class="box product-list">
                            <h3>daftar pesanan</h3>
                            <div class="row-100 second-top">
                                <div class="col search">
                                    <div class="container-search">
                                        <form v-on:submit.prevent="listOrder(pagination.page = 1)">
                                            <input v-model="pagination.search" @keyup="listOrder(pagination.page = 1)" name="search" class="input-search" type="search" id="search" autocomplete="off" value="" placeholder="Masukan Nomor Invoice" />
                                            <img class="ic_search" src="/img/ic_search.svg" alt=""> 
                                        </form>
                                    </div>
                                </div>
                                <div class="col select date">
                                    <h5>tanggal pesanan</h5>
                                    <input v-model="pagination.date" type="date" name="daterange" value="11/01/2021 - 11/15/2021" @change="listOrder(pagination.page = 1)" />
                                </div>
                                <filter-master-status-order  @updatePage="updatePage($event)"></filter-master-status-order>
                            </div>
                            <div class="row-100">
                                <div class="order-product">
                                    <div class="head-data">
                                        <ul>
                                            <li style="width:20%">detail pembelian</li>
                                            <li style="width:25%">produk</li>
                                            <li style="width:15%">Total transaksi</li>
                                            <li style="width:10%">status</li>
                                            <li style="width:15%">jasa pengiriman</li>
                                            <li style="width:15%"></li>
                                        </ul>
                                    </div>
                                    <div class="body-data">
                                        <div v-if="Object.keys(list).length > 0">
                                            <div class="row-data" v-for="(item , index) in list" :key="index">
                                                <div class="col" style="width:20%">
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
                                                        <h6 v-if="item.status_order != null">{{item.status_order.description}}</h6>
                                                    </div>
                                                </div>
                                                <div class="col" style="width:15%">
                                                    <div>
                                                        <h6>{{ item.shipping.rate | SHIPPING }}</h6>
                                                        <div v-if="item.shipping.pickup_time != null">
                                                            <h6>pengiriman</h6>
                                                            <h6 style="color: #9494CB;">{{item.shipping.pickup_time}}</h6>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col" style="width:15%">
                                                    <div>
                                                        <h6 @click="show_modal_detail(item.invoice_number , index)" style="cursor:pointer; color: #9494CB;">rincian pemesanan</h6>
                                                        <button v-if="item.status_order.id == 2" @click="process_order(item.invoice_number , 'processed')">proses</button>
                                                        <button v-if="item.status_order.id == 3" @click="updateStatusInvoiceCreatePickup(item.invoice_number , 'waiting_delivery')">Pick Up</button>
                                                        <button v-if="item.status_order.id == 14 || item.status_order.id == 4" @click="print_label(item.invoice_number)">print</button>
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
        </div>
        <modal-detail-pesanan :invoice="invoice_response"  :track="track" :logs="selected_logs" :status_code="status_code"></modal-detail-pesanan>
        <modal-cancel-order :invoice="invoice_to_process" @action="actionAfterProcess($event)"></modal-cancel-order>
        <modal-load></modal-load>
    </div>
</template>

<script>
import apiVendor from '../../apis/Vendor'
import ModalDetailPesanan from '../desktop/modal/ModalDetailPesanan.vue'
import FilterMasterStatusOrder from '../pagination/FilterMasterStatusOrder.vue'
import Pagination from '../pagination/pagination.vue'
import ModalCancelOrder from './modal/ModalCancelOrder.vue'
import Message from '../../utils/Message'
import BlankPage from '../desktop/BlankPage.vue'
import ModalLoad from '../desktop/modal/ModalLoading.vue'
export default {
    data(){
        return {
            invoice_to_process : '',
            list : {},
            invoice_response : {},
            selected_logs : {},
            track : {},
            is_loading : true,
            status_code: null,
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
        this.listOrder()
    },
    methods : {
        set_breadcrumb() {
            let slug = this.$route.params.pathMatch
            let product_name = slug.replaceAll("-", " ");
            if (this.$route.meta.breadcrumb.length >= 3) {
                this.$route.meta.breadcrumb.pop()
            }
            this.$route.meta.breadcrumb.push({name: product_name, link: '/product-detail/' + slug})
        },
        listOrder(){
            this.timer = setTimeout(() => {
                apiVendor.listOrder(this.pagination).then( response =>{
                    this.list = response.data.data.orders
                    this.pagination.total = response.data.data.total
                    this.pagination.current = response.data.data.current_page
                    this.track = response.data.data.tracker
                    this.is_loading = false
                });
            }, 500);
        },
        updateStatusInvoice(invoice_number , status_order , reason = ""){
            $("#modal_load").fadeIn();

            apiVendor.updateStatusInvoice({invoice_number : invoice_number , status_code : status_order , reason:reason}).then( response =>{
                $("#modal_load").fadeOut();
                return location.reload()
                // if(response.data.code == 200){
                //    alert(response.data.data.message);
                //    return location.reload()
                // }
                // alert(response.data.message)
                // Message.alert(response.data.message , 'Informasi' , 3000)
            });
        },
        process_order(invoice_number , status_order){
            this.invoice_to_process = '';
            this.invoice_to_process = invoice_number;
            $("#cancel_order").fadeIn(function () {
                $("#cancel_order").addClass('overflow-scroll')
                $("body").addClass('overflow-hidden');
            });
        },
        actionAfterProcess(event){
            if(event.action == 'cancel'){
                this.updateStatusInvoice(this.invoice_to_process , 'vendor_canceled' , event.reason);
            }
            if(event.action == 'process'){
                this.updateStatusInvoice(this.invoice_to_process , 'processed', event.reason);
            }
            return;
        },
        updateStatusInvoiceCreatePickup(invoice_number , status_order){
            apiVendor.updateStatusInvoiceCreatePickup({invoice_number : invoice_number , status_code : status_order}).then( response =>{
                if(response.status == 200){
                    alert(response.data.message);
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
            this.listOrder()
        },
        show_modal_detail(invoice , index){
            $("#detail_modal").fadeIn(function () {
                $("#detail_modal").addClass('overflow-scroll')
                $("body").addClass('overflow-hidden');
            });
            this.selected_logs = {}
            this.invoice_response = {}
            this.selected_logs = this.list[index].order_logs;
            apiVendor.transactionByInvoiceNumber({invoice : invoice}).then( response => {
                this.invoice_response = response.data.data
                this.status_code = response.data.data.status_code
            });

        },
        print_label(invoice){
            apiVendor.printLabel({invoice:invoice}).then(response => {
                if(response.status == 200){
                   window.open(response.data.data, '_blank');
                }
            })
        }
    },
    components :{
        Pagination,
        FilterMasterStatusOrder,
        ModalDetailPesanan,
        ModalCancelOrder,
        Message,
        BlankPage, ModalLoad
    }
}
</script>
