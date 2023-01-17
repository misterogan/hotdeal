<template>
   <div class="flex-basis-60 max-width-60">
        <div class="box3-shadow-white">
            <div v-if="is_loading">
                <div class="ph-row mtop-30 mbottom-30">
                    <div class="ph-col-3 bg-placeholder"></div>
                </div>
                <div class="ph-row mbottom-30">
                    <div class="ph-col-1 mright-5 bg-placeholder"></div>
                    <div class="ph-col-8 bg-placeholder"></div>
                </div>
                <div class="ph-row mbottom-20">
                    <div class="ph-col-2 mright-5 bg-placeholder"></div>
                    <div class="ph-col-4 bg-placeholder"></div>
                </div>
                <div class="ph-row justify-between mbottom-20">
                    <div class="ph-col-4 bg-placeholder ph-h75 mright-30 rounded-sm"></div>
                    <div class="ph-col-2 bg-placeholder ph-h75 mright-10 rounded-sm"></div>
                    <div class="ph-col-2 bg-placeholder ph-h75 mright-10 rounded-sm"></div>
                    <div class="ph-col-2 bg-placeholder ph-h75 mright-10 rounded-sm"></div>
                    <div class="ph-col-2 bg-placeholder ph-h75 rounded-sm"></div>
                </div>

                <div class="ph-row justify-between mbottom-10">
                    <div class=" ph-col-3 bg-placeholder mright-20"></div>
                    <div class=" ph-col-2 bg-placeholder mright-10"></div>
                    <div class=" ph-col-2 bg-placeholder mright-20"></div>
                    <div class=" ph-col-3 bg-placeholder mright-20"></div>
                </div>
                <div class="ph-row justify-between mbottom-10">
                    <div class=" ph-col-3 bg-placeholder mright-20"></div>
                    <div class=" ph-col-1 bg-placeholder mright-10"></div>
                    <div class=" ph-col-1 bg-placeholder mright-20"></div>
                    <div class=" ph-col-3 bg-placeholder mright-20"></div>
                </div>
                <div class="ph-row justify-between mbottom-20">
                    <div class=" ph-col-3 bg-placeholder mright-20"></div>
                    <div class=" ph-col-1 bg-placeholder mright-10"></div>
                    <div class=" ph-col-1 bg-placeholder mright-20"></div>
                    <div class=" ph-col-3 bg-placeholder mright-20"></div>
                </div>
                <div class="ph-row justify-between mbottom-30">
                    <div class=" ph-col-3 bg-placeholder mright-20"></div>
                    <div class=" ph-col-2 bg-placeholder mright-10"></div>
                    <div class=" ph-col-2 bg-placeholder mright-20"></div>
                    <div class=" ph-col-3 bg-placeholder mright-20"></div>
                </div>
                <div class="ph-row justify-between mbottom-20">
                    <div class="ph-col-5 ph-h50 bg-placeholder"></div>
                </div> 
                <div class="ph-row mtop-20">
                    <div class="ph-col-10 bg-placeholder"></div>
                </div>
            </div>
            <div v-if="!is_loading">
                <h2 class="mbottom-30">Daftar Transaksi</h2>
                <div class="filter mbottom-20">
                    <img class="toggle-filter" src="/img/assets_hotdeal_filter.svg" alt="">
                    <div class="overlay-transparent d-none"></div>
                    <ul class="small-box-filter">
                        <li class="filter-title-mobile">
                            <div class="fs-12 fp-black pl-12">Filter</div>
                            <img class="toggle-filter" src="/img/assets_hotdeal_filter.svg" alt="">
                        </li>
                        <li v-bind:class="paginate.filter == 'all' ? 'active' : ''" @click="orderList(paginate.filter = 'all' , paginate.page = 1)">Semua Transaksi</li>
                        <li v-bind:class="paginate.filter == 'processed' ? 'active' : ''" @click="orderList(paginate.filter = 'processed', paginate.page = 1)">Dalam Proses</li>
                        <li v-bind:class="paginate.filter == 'completed' ? 'active' : ''" @click="orderList(paginate.filter = 'completed', paginate.page = 1)">Selesai</li>
                        <li v-bind:class="paginate.filter == 'refunded' ? 'active' : ''" @click="orderList(paginate.filter = 'refunded', paginate.page = 1)">Refund</li>
                        <li v-bind:class="paginate.filter == 'cancelled' ? 'active' : ''" @click="orderList(paginate.filter = 'cancelled', paginate.page = 1)">Dibatalkan</li>
                    </ul>
                </div>
                <div  v-if="Object.keys(list).length > 0">
                    <div class="transaction-history" v-for="(item, index) in list" :key="index" v-show="item.order_detail_product.length > 0">
                        <div class="transaction-status">
                            <h5 class="status processing mright-10">{{item.status_order.description}}</h5>
                            <div class="transaction-date">
                                <h5 class="fw-700 mright-5">Transaksi Tanggal</h5>
                                <h5 class="fw-700">{{item.created_at}}</h5>
                            </div>
                        </div>
                        <div class="summary-transaksi">
                            <div class="col-seller">
                                <div class="content-seller">
                                    <div class="col image-product">
                                        <img class="img-seller" :src="item.vendor_id.image ? item.vendor_id.image : '/img/img_seller.svg' " alt="">
                                    </div>
                                    <div class="col">
                                        <h6>Penjual :</h6>
                                        <h6 class="name-seller">{{item.vendor_id.vendor_name}}</h6>
                                        <h6 class="address-seller">{{item.vendor_id.city}}, {{item.vendor_id.province}}</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-product">
                                <div v-if="item.order_detail_product != null && item.order_detail_product.length > 0" class="slider-products">
                                    <div class="container-slick" v-for="(product, pindex) in item.order_detail_product " :key="pindex">
                                        <router-link v-if="product.product_info != null" :to="'/product-detail/'+product.product_info.slug">
                                            <img :src="product.product_info.image.link" :alt="product.product_info.name">
                                        </router-link>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-product">
                                <VueSlickCarousel 
                                    v-if="item.order_detail_product != null && item.order_detail_product.length > 0" 
                                    class="slider-products" 
                                    :arrows="item.order_detail_product.length > 3" 
                                    :dots="false" 
                                    :autoplay="false" 
                                    :infinite="false" 
                                    :speed="300" 
                                    :variableWidth= "true" 
                                    :focusOnSelect="false">
                                    <div class="container-slick" v-for="(product, pindex) in item.order_detail_product " :key="pindex"> 
                                        <router-link v-if="product.product_info != null" :to="'/product-detail/'+product.product_info.slug">
                                            <img :src="product.product_info.image.link" :alt="product.product_info.name">
                                        </router-link>
                                    </div>
                                </VueSlickCarousel>
                            </div>
                        </div>
                        <div class="list-transaksi">
                            <div class="col-4 product-name-trans" v-if="item.order_detail_product != null">
                                <ul>
                                    <li class="active product-name" v-for="(product, pindex) in item.order_detail_product " :key="pindex" >
                                        <h5 class="fw-700" v-if="product.product_info != null">{{product.product_info.name}}</h5></li>
                                    <li>
                                        <h5>{{item.order_detail_product.length}} barang</h5>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-4 product-detail-price">
                                <ul>
                                    <li>
                                        <h5>Total {{ item.order_detail_product.length }} Produk</h5>
                                        <h5>{{(parseInt(item.invoice_total_payment)) | RupiahFormat}}</h5>
                                    </li>
                                    <li>
                                        <h5>Diskon</h5>
                                        <h5> - {{item.total_discount | RupiahFormat}}</h5>
                                    </li>
                                    <li>
                                        <h5>Ongkos kirim</h5>
                                        <h5>{{item.shipping.shipping_cost | RupiahFormat}}</h5>
                                    </li>
                                    <li v-if="item.status_order.id == 4">
                                        <h5>Estimasi</h5>
                                        <h5>{{ item.estimasi }}</h5>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-4 product-invoice">
                                <ul>
                                    <li class="invoice" @click="show_modal_invoice(item.invoice_number , index)">
                                        <h5 class="fw-700 fp-purple">Invoice</h5>
                                        <h5 class="fp-purple">{{item.invoice_number}}</h5>
                                    </li>
                                     <li v-if="item.status_order.id == 8 ||item.status_order.id == 9 || item.status_order.id == 10">
                                        <router-link :to="'/transactions/cancel-transaction?invoice='+item.invoice_number">
                                            <h5 class="fp-purple fw-700">detail pembatalan</h5>
                                        </router-link>
                                    </li>
                                    <li v-if="item.status_order.id == 6 || item.status_order.id == 7">
                                        <router-link class="btn-link" :to="'/transactions/refund-transaction?invoice='+item.invoice_number">
                                            <h5 class="fp-purple fw-700">detail refund</h5>
                                        </router-link>
                                    </li>
                                    <li v-else class="detail" href="javascript:void(0)" @click="show_modal_detail(item.invoice_number , index)">
                                        <h5 class="fp-purple fw-700">detail pesanan</h5>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row-btn mtop-20">
                            <div class="progress-transaction">
                                <ul class="progressbar" v-if="Object.keys(track).length > 0">
                                    <li :class="'active'">
                                        <img src="/img/ic_checklist_purple.svg" alt="">
                                    </li>
                                    <li :class="item.order_logs[t_index] != undefined ? 'active' : '' " v-for="(t_item , t_index) in track" :key="t_index">
                                        <img src="/img/ic_checklist_purple.svg" alt="">
                                    </li>
                                    <li class="cancel" v-if="item.status_order.id == 8 ||item.status_order.id == 9 || item.status_order.id == 10"></li>
                                    <li class="refund" v-if="item.status_order.id == 6 ||item.status_order.id == 7"></li>
                                </ul>
                            </div>
                            <div class="button">
                                <button class="btn-secondary refund" href="javascript:void(0)" @click="show_refund_confirm(item.invoice_number)" v-if="item.status_order.status_code == 'arrived' " >
                                    <img src="/img/ic_btn_refund.svg" alt="">
                                    ajukan refund
                                </button>
                                <button class="btn-primary" href="javascript:void(0)" @click="orderComplete(item.invoice_number , index)" v-if="item.status_order.status_code == 'arrived' ">
                                    pesanan selesai
                                </button>
                                <button class="btn-primary" v-if="item.status_order.status_code == 'completed' && item.review.length == 0 && item.interval <= 30" @click="show_modal_review(item.invoice_number , index)"  >
                                     <img src="/img/ic_ulasan.svg" alt="">
                                     beri ulasan
                                 </button>
                                 <button class="btn-secondary" v-if="item.status_order.status_code == 'completed' && item.review.length > 0 && item.interval <= 30" @click="show_modal_view_review(item.invoice_number , index , rev=1)"  >
                                    <img src="/img/ic_lihat_ulasan.svg" alt="">
                                    lihat ulasan
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- <pagination-2 :total="paginate.total" :current="paginate.current_page" @changeAction="changeActionPagination($event)"></pagination-2> -->
                    <button class="btn-loadmore mtop-20" @click="loadMore" v-if="btn_loadmore">selanjutnya</button>
                </div>
                <div v-else>
                    <blank-page :message="'Belum Ada Pesanan'" :image="'/img/animation_empty_order.svg'"></blank-page>
                </div>
            </div>
        </div>
        <modal-invoice :invoice="invoice_modal"></modal-invoice>
        <modal-detail-pesanan :invoice="invoice_response" :track="track" :logs="selected_logs" :status_code="status_code" ></modal-detail-pesanan>
        <modal-complete @modal_review="modal_review" :data="product_for_review" @review_now="review_now"></modal-complete>
        <modal-review :data="product_for_review" :review="user_review"></modal-review>
        <modal-view-review :data="product_for_review" :review="user_review"></modal-view-review>
        <modal-refund :invoice="invoice_selected"></modal-refund>
    </div>
</template>

<script>
    import apiCustomer from '../../../../apis/Customer'
    import VueSlickCarousel from 'vue-slick-carousel'
    import 'vue-slick-carousel/dist/vue-slick-carousel.css'
    import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
    import Modal from '../../Modal.vue'
    import ModalInvoice from '../../modal/ModalInvoice.vue'
    import ModalDetailPesanan from '../../modal/ModalDetailPesanan.vue'
    import ModalComplete from '../../modal/ModalComplete.vue'
    import ModalReview from '../../modal/ModalReview.vue'
    import ModalViewReview from '../../modal/ModalViewReview.vue'
    import ModalRefund from '../../modal/ModalRefund.vue'
    import BlankPage from '../../BlankPage.vue'
    import Message from '../../../../utils/Message'
    import Pagination2 from '../../../pagination/v2/pagination.vue'

    export default {
        name: "OrderTransactionList.vue",
        data(){
            return {
                invoice_selected : '',
                list : {},
                track : {},
                product_for_review : {},
                user_review : {},
                selected_logs : {},
                invoice_response : {},
                invoice_modal: {},
                is_loading:  true,
                status_code: null,
                paginate : {
                    page : 1,
                    current_page : 1,
                    total : 1,
                    filter : 'all'
                },
                details_id : null,
                btn_loadmore : true,
                // slickOptions:{
                //     arrows: false,
                //     dots: false,
                //     autoplay: false,
                //     infinite: true,
                //     speed: 300,
                //     slidesToShow: 3,
                //     variableWidth: true,
                //     adaptiveHeight: false
                // }
            };
        },
        mounted(){
           this.orderList();
        },
        // beforeUpdate() {
        //     if (this.$refs.slick) {
        //         this.$refs.slick.destroy();
        //     }
        // },
        // updated() {
        //     this.$nextTick(function () {
        //         if (this.$refs.slick) {
        //             this.$refs.slick.create(this.slickOptions);
        //         }
        //     });
        // },
        methods: {
            orderList(){
                apiCustomer.transactionList(this.paginate).then( response => {
                   if(response.data.code == 200){
                        if(this.paginate.page == 1){
                            this.list = response.data.data.order_details
                        }else{
                            this.list.push.apply( this.list ,response.data.data.order_details)
                        }
                        this.track = response.data.data.tracker
                        this.paginate.page = response.data.data.current_page
                        this.paginate.current_page = response.data.data.current_page
                        this.paginate.total = response.data.data.total
                        this.is_loading = false

                   }
                   if(Object.keys(response.data.data.order_details).length < 10){
                        this.btn_loadmore = false
                    }
                    this.is_loading = false
                    $("#modal_load").fadeOut();
                });
            },
            loadMore(){
                $("#modal_load").fadeIn();
                this.paginate.page++;
                this.orderList();
                // $('.slider-products').slick();
            },
            show_refund_confirm(invoice){
                 this.invoice_selected = invoice;
                 $("#refund_confirm_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_wait(){
                $("#waiting_payment_modal").fadeIn(function () {
                    // $("body").addClass('overflow-hidden');
                });
            },
            show_modal_detail(invoice , index ){
                this.selected_logs = {}
                this.invoice_response = {}
                this.selected_logs = this.list[index].order_logs;
                apiCustomer.transactionByInvoiceNumber({invoice : invoice}).then( response => {
                    this.invoice_response = response.data.data
                    this.status_code = response.data.data.status_code
                    $("#detail_modal").fadeIn(function () {
                        $("#detail_modal").addClass('overflow-scroll')
                        $("body").addClass('overflow-hidden');
                    });
                });
            },
            show_modal_invoice(invoice, index){
                this.selected_logs = {}
                this.invoice_modal = {}
                this.selected_logs = this.list[index].order_logs;
                apiCustomer.modalInvoice({invoice : invoice}).then( response => {
                    this.invoice_modal = response.data.data
                    $("#invoice_modal").fadeIn(function () {
                        $("#invoice_modal").addClass('overflow-scroll')
                        $("body").addClass('overflow-hidden');
                    });
                });
            },
            show_modal_complete(){
                $("#complete_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_review(inv = null , index = null){
                if(inv != null){
                    this.product_for_review = {vendor : this.list[index].vendor_id, inv : inv , products : this.list[index].order_detail_product, order_details_id : this.list[index].id, review : this.list[index].review}
                }
                $("#review_modal").fadeIn(function () {
                    $("#review_modal").addClass('overflow-scroll')
                    $("body").addClass('overflow-hidden');
                });
            },
            review_now(inv){
                this.show_modal_review(inv);
            },
            modal_review(){
                this.show_modal_review()
            },
            show_modal_view_review(inv = null , index = null , rev){
                if(inv != null){
                    this.product_for_review = {vendor : this.list[index].vendor_id, inv : inv , products : this.list[index].order_detail_product, order_details_id : this.list[index].id, review : this.list[index].review}
                }
                $("#view_review_modal").fadeIn(function () {
                    $("#view_review_modal").addClass('overflow-scroll')
                    $("body").addClass('overflow-hidden');
                });
                $("#complete_modal").fadeOut(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            modal_view_review(){
                this.show_modal_review()
            },
            orderComplete(inv , index){
                this.product_for_review = {vendor : this.list[index].vendor_id, inv : inv , products : this.list[index].order_detail_product, order_details_id : this.list[index].id, review : this.list[index].review}
                apiCustomer.completeOrder({invoice : inv}).then( response => {
                    if(response.data.code == 200){
                       this.show_modal_complete()
                       this.list[index].status_order.status_code = 'completed'
                    }else{
                        Message.alert('Terjadi kesalahan saat memproses pesanan');
                    }
                });
            },
            changeActionPagination(event){
                if(event < 1 || event > this.paginate.total){
                    return false;
                }
                this.paginate.page = event
                this.orderList()
            }
        },
        components : {
                VueSlickCarousel,
                Modal,
                ModalInvoice,
                ModalDetailPesanan,
                ModalComplete,
                ModalReview,
                ModalRefund,
                BlankPage,
                Message,
                ModalViewReview,
                Pagination2
        }
    }
</script>

<style scoped>

</style>
