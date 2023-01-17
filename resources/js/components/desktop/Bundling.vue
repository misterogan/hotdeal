<template>
    <div>
        <div class="event-container">
            <div class="banner-shadow">
                <card-skeleton :count="1" v-if="is_loading"></card-skeleton>
                <div class="container-banner" v-if="!is_loading">
                    <img :src="event.banner" alt="">
                </div>
                <div class="col-100p cs-text-center" v-if="!is_loading">
                    <div class="bundling-header p-20">
                        <div class="card-title">
                            <h3 class="cs-f-18">{{event.event_name}}</h3>
                        </div>
                        <div class="periode">
                            <div class="title">
                                <p>Periode Event</p>
                            </div>
                            <div class="date cs-text">
                                <h4>{{periode}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mtop-20">
                <div class="bundling-tab">
                    <div class="row cs-justify-space wrap">
                        <div class="col-50 active cs-text pointer" v-bind:class="menu_active == 'first' ? 'cs-active' : '' " @click="tab('first')" id="event-content">
                            <h4>event konten</h4>
                            <hr class="cs-break">
                        </div>
                        <div class="col-50 cs-text pointer" v-bind:class="menu_active == 'second' ? 'cs-active' : '' " @click="tab('second')" id="ticket-accumulation">
                            <h4>akumulasi tiket</h4>
                            <hr class="cs-break">
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="content " v-show="menu_active == 'first'">
                            <div class="section">
                                <div class="title cs-text cs-text-center pt-20">
                                    <h4>Mekanisme Undian</h4>
                                    <hr class="cs-break-1 cs-w-60">
                                </div>
                                <div class="cs-tnc pt-20 cs-w-90 m-auto">
                                    <ul>
                                        <li> 
                                            <div class="row p-10">
                                                <div class="cs-col-10 cs-flex-items"> <img class="m-20" src="/img/ic_bundling_cart.svg"></div>
                                                <div class="col-90 cs-flex-items pl-20 pr-20" > 
                                                    <h4 class="cs-p5">1</h4>  
                                                    <span>Beli produk spesial bundling STIMMUNG BT21 Limited Edition.</span> </div>
                                            </div>
                                        </li>
                                        <li> 
                                            <div class="row p-10">
                                                <div class="cs-col-10 cs-flex-items"> <img src="/img/ic_bundling_2.svg"> </div>
                                                <div class="col-90 cs-flex-items pl-20 pr-20">
                                                    <h4 class="cs-p5">2</h4> 
                                                    <span>Setiap transaksi pembelian produk bundling akan mendapatkan kupon undian.</span> 
                                                </div>
                                            </div>
                                        </li>
                                        <li> 
                                            <div class="row p-10">
                                                <div class="cs-col-10 cs-flex-items"> <img src="/img/ic_bundling_3.svg"></div>
                                                <div class="col-90 cs-flex-items pl-20 pr-20">  
                                                    <h4 class="cs-p5">3</h4>  
                                                    <span>Tim Hotdeal akan mengundi semua kupon undian di akhir periode event.</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li> 
                                            <div class="row p-10">
                                                <div class="cs-col-10 cs-flex-items"> <img src="/img/ic_bundling_4.svg"></div>
                                                <div class="col-90 cs-flex-items pl-20 pr-20">   
                                                    <h4 class="cs-p5">4</h4> 
                                                    <span>Pemenang Album BTS PROOF OFFICIAL Standard Edition akan diumumkan di website Hotdeal.</span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="section pt-10">
                                <div class="title pointer d-flex align-center justify-center" @click="term_show()" >
                                    <h4 class="mright-10">Syarat &amp; Ketentuan</h4>
                                    <img class="term-arrow pointer" src="/img/arrow_purple.svg" alt="" width="10">
                                </div>
                                <div class="cs-tnc pl-20 pr-20 cs-tnc-toogle" style="display:none"> 
                                    <p v-html="event.tnc"></p>
                                </div>
                            </div>
                        </div>
                        <div class="akumulasi pt-20" v-show="menu_active == 'second'" >
                            <list-skeleton v-if="is_searching" :count="10"></list-skeleton>
                            <div v-if="Object.keys(ticket_list).length > 0">
                                <div v-for="(item , index) in ticket_list" :key="index">
                                    <div class="title-akumulasi cs-justify-between cs-flex-items cs-text">
                                        <h4 class=""> Pesanan {{format_date(item.transaction.created_at)}} </h4> 
                                        <span class="f-12">{{item.transaction.transaction_number}}</span>
                                    </div>
                                    <div class="body-akumulasi f-12">
                                        <div class="row cs-w-100 cs-justify-between pt-5" v-for="(invoice, index1) in item.invoice" :key="index1">
                                            <div class="col-50">
                                                <span class="product cs-text-left">{{invoice.invoice_number}}</span>
                                                <br>
                                                <span class="product  cs-text-left"> Pembelian {{Object.keys(item.product).length}} produk.</span>
                                            </div>
                                            <div class="col-50" style="text-align : end">
                                                <span class="product">{{invoice.invoice_total_payment | RupiahFormat }}</span>
                                                <br>
                                                <span class="product cs-color-violet cs-text-right pointer" @click="show_modal_invoice(invoice.invoice_number)" >Invoice</span>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="action-akumulasi pt-20">
                                        <div class="row total-coupon cs-justify-between cs-border-top-left cs-border-top-right cs-bg-primary p-20 w-100" style="box-sizing:border-box"> 
                                            <span class="font-bg-primary">Total Kupon Undian </span>  
                                            <span class="font-bg-primary badge"> {{Object.keys(item.detail).length}} </span>
                                        </div>
                                        <div @click="modal_detail(item.detail)" class="row detail-coupon cs-justify-between cs-border-bottom-left cs-border-bottom-right cs-bg-dark-primary p-20 w-100" style="box-sizing:border-box"> 
                                            <b  class="font-bg-primary pointer"> Lihat Kupon </b> 
                                            <span class="font-bg-primary badge pointer">
                                                <img src="/img/assets_arrow_white.svg" alt="">
                                            </span> 
                                        </div>
                                    </div>
                                    <br>
                                    <hr>
                                </div>
                            </div>
                            <div class="akumulasi p-20" v-show="menu_active == 'second'" v-else>
                                <blank-page :message="'Kamu Tidak Mempunyai Ticket'" :image="'/img/animation_empty_search.svg'"></blank-page>
                            </div>
                            <pagination :total="total_page" :current="current_page" @changeAction="changeActionPagination($event)"></pagination>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="col2-flex">
                <div class="col-80p" style="flex-basis: 100%!important">
                    <div class="sticky-product">
                        <div class="section">
                            <div class="row-100">
                                <h5 style="padding: 0 0 10px 5px;color:#44454f; width:50% !important">Produk Bundling </h5> 
                                <router-link to="/bundling/product" style="width:50% !important;text-align: right;"><h5> Lihat Semua</h5></router-link>
                            </div>
                            <card-skeleton :count="2" v-if="is_loading"></card-skeleton>
                            <div class="row-100 product-bundling" v-else-if="!is_loading && Object.keys(PList).length > 0">
                                <small-card-product-without-stock :item="item" v-for="(item , index) in PList"  :key="index" @updateCarts="$emit('updateCarts' , $event)"></small-card-product-without-stock>
                            </div>
                            <div v-else>
                                <blank-page :message="'Stok bundling kosong'" :image="'img/animation_empty_search.svg'"></blank-page>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <modal-ticket :ticket="ticket"></modal-ticket>
        <modal-invoice :invoice="invoice_modal"></modal-invoice>
    </div>
    
</template>
<script>
    import ProfileMenu from './component/profile/ProfileMenu.vue'
    import User from "../../apis/User";
    import Customer from "../../apis/Customer";
    import BlankPage from './BlankPage.vue';
    import SmallCardProductWithoutStock from './component/product/SmallCardProductWithoutStock.vue';
    import apiCustomer from '../../apis/Customer'
    import ModalTicket from './modal/ModalTicket.vue'
    import ModalInvoice from './modal/ModalInvoice.vue';
    import pagination from '../pagination/v2/pagination.vue';
    import CardSkeleton from '../skeleton/CardSkeleton.vue';
    import ListSkeleton from '../skeleton/ListSkeleton.vue';
    import moment from 'moment'

    export default {
        name: "Bundling.vue",
        components: {
                ProfileMenu,
                BlankPage,
                SmallCardProductWithoutStock,
                Customer,
                User,
                ModalTicket,
                ModalInvoice,
                pagination,
                CardSkeleton,
                ListSkeleton
        },
        data() {
            return{
                menu_active : 'first',
                tab_content : '',
                term_content : '',
                PList : {},
                ticket_list : {},
                event : {},
                periode : '',
                ticket : {},
                invoice_modal: {},
                pagination : {
                    page : 1,
                    current : 1,
                    total : 1,
                    status : '',
                    date : '',
                    search : '',
                    filter : '',
                    perpage : 5
                },
                total_page : '',
                current_page : '',
                page : 1,
                is_loading : true,
                is_searching : true
            }
        },
        created() {
        },
        mounted() {
            $('.akumulasi').hide();
            this.get_tickets();
            this.get_detail_event();
            this.product()
        },
        methods : {
            modal_detail(data){
                this.ticket = data;
                $("#modal_ticket_bundling").fadeIn(function () {
                    $("#modal_ticket_bundling").addClass('overflow-scroll');
                });
            },
            get_tickets() {
                const fd = new FormData();
                fd.append('page' , this.page)
                Customer.user_raffle_tickets(fd).then(response => {
                    if(Object.keys(response.data.data).length > 0){
                        this.ticket_list = response.data.data.data
                        this.total_page = response.data.data.total
                        this.current_page = response.data.data.current_page
                    }
                    this.is_searching = false
                })
            },
            get_detail_event() {
                Customer.detail_event().then(response => {
                    
                    if(Object.keys(response.data.data).length < 1){
                        return;
                    }
                    this.event = response.data.data

                    if(this.event.start_month == this.event.end_month){
                        this.periode = this.event.start_date + ' - ' + this.event.end_date + ' ' + this.event.end_month + ' ' + this.event.end_year
                    } else {
                        this.periode = this.event.start_date + ' ' + this.event.start_month + ' - ' + this.event.end_date + ' ' + this.event.end_month + ' ' + this.event.end_year
                    }
                    this.is_loading = false
                })
            },
            product(){
                this.is_loading = true,
                apiCustomer.productBundlingRandom({keyword : ''}).then(response => {
                    this.PList = response.data.data;
                    this.is_loading = false
                })
            },
            tab(id){
               this.menu_active = id;
            },
            term_show(){
                $(".cs-tnc-toogle" ).slideToggle(1000);
                $(".term-arrow" ).toggleClass('flip');
            },
            format_date(date){
                if (date){
                    return moment(String(date)).format('DD/MM/YYYY hh:mm')
                }
            },
            show_modal_invoice(invoice, index){
                this.selected_logs = {}
                this.invoice_modal = {}
                apiCustomer.modalInvoice({invoice : invoice}).then( response => {
                    this.invoice_modal = response.data.data
                    $("#invoice_modal").fadeIn(function () {
                        $("#invoice_modal").addClass('overflow-scroll')
                        $("body").addClass('overflow-hidden');
                    });
                });
            },
            changeActionPagination(event){
                 if(event < 1 || event > this.total_page){
                    return false;
                 }
                 this.Pagination(event)
            },
            Pagination(page){
                this.$router.replace({path:'/bundling', query : {page : page , search : this.keyword}})
                this.page =  page
                this.is_searching = true
                this.get_tickets();
            },
        },
        computed : {
            
        }
    }
</script>




