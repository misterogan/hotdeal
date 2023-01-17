<template>
    <div class="row-hd">
        <div class="row col3-flex">
            <div class="flex-basis-40">
                <profile-menu></profile-menu>
                <profile-point-info v-if="Object.keys(profile).length > 0" :profile="profile.users"></profile-point-info>
            </div>
            <div class="flex-basis-60">
                <div class="box3-shadow-white">
                    <div v-if="is_loading">
                        <div class="ph-row mtop-20 mbottom-40">
                            <div class="ph-col-2 bg-placeholder"></div>
                        </div>
                        <div class="ph-row mbottom-30">
                            <div class="ph-col-12 ph-h150 rounded-md bg-placeholder"></div>
                        </div>
                        <div class="ph-row mbottom-20">
                            <div class="ph-col-3 ph-h75 rounded-sm bg-placeholder mright-10"></div>
                            <div class="ph-col-3 ph-h75 rounded-sm bg-placeholder"></div>
                        </div>
                        <div class="ph-row justify-between mbottom-20">
                            <div class="d-block ph-col-9 bg-placeholder"></div>
                        </div>
                        <div class="ph-row mbottom-20">
                            <div class="ph-col-3 bg-placeholder mright-10"></div>
                            <div class="ph-col-4 bg-placeholder"></div>
                        </div>
                        <div class="ph-row mbottom-20 justidy-between">
                            <div class="ph-col-3 bg-placeholder ph-h75 mright-5"></div>
                            <div class="ph-col-3 bg-placeholder ph-h75 mright-5"></div>
                            <div class="ph-col-3 bg-placeholder ph-h75 mright-5"></div>
                            <div class="ph-col-3 bg-placeholder ph-h75 mright-5"></div>
                            <div class="ph-col-3 bg-placeholder ph-h75 mright-5"></div>
                        </div>  
                    </div>
                    <div v-if="!is_loading">
                        <h2 class="mbottom-30">Rejeki Nomplok</h2>
                        <rejeki-nomplok-banner></rejeki-nomplok-banner>
                        <div class="d-flex align-stretch mtop-20 mbottom-20 mcoupon-top-info">
                            <div class="coupon-number" @click="couponList(pagination.filter = 'active' , pagination.page = 1)">
                                <h5>kupon kamu</h5>
                                <p>{{Object.keys(coupon).length}}</p>
                                <img class="pointer" src="/img/assets_arrow_white.svg" alt="">
                            </div>
                            <div class="coupon-period" v-if="Object.keys(active_period).length > 0">
                                <h5>rejeki nomplok periode aktif</h5>
                                <div class="d-flex align-center justify-between">
                                    <span>Week {{active_period.week}}</span>
                                    <span>{{active_period.start_date}} - {{active_period.end_date}}</span>
                                </div>
                                <router-link :to="'/rejeki-nomplok'">
                                    <img class="pointer" src="/img/assets_arrow_white.svg" alt="">
                                </router-link>
                            </div>
                        </div>
                        <div class="filter">
                            <img class="toggle-filter" src="/img/assets_hotdeal_filter.svg" alt="">
                            <div class="overlay-transparent d-none"></div>
                            <ul class="small-box-filter">
                                <li class="filter-title-mobile">
                                    <div class="fs-12 fp-black pl-12">Filter</div>
                                    <img class="toggle-filter" src="/img/assets_hotdeal_filter.svg" alt="">
                                </li>
                                <li v-bind:class="pagination.filter == 'all' ? 'active' : ''" @click="couponList(pagination.filter = 'all' , pagination.page = 1)">Semua </li>
                                <li v-bind:class="pagination.filter == 'active' ? 'active' : ''" @click="couponList(pagination.filter = 'active' , pagination.page = 1)">aktif</li>
                                <li v-bind:class="pagination.filter == 'expired' ? 'active' : ''" @click="couponList(pagination.filter = 'expired' , pagination.page = 1)">hangus</li>
                                <li v-bind:class="pagination.filter == 'winner' ? 'active' : ''" @click="couponList(pagination.filter = 'winner' , pagination.page = 1)">Menang</li>
                            </ul>
                        </div>
                        <div class="coupon-history" v-if="Object.keys(coupon).length > 0 ">
                            <div class="list" v-for="(item, index) in coupon" :key="index">
                                <div class="top-coupon d-flex align-center mbottom-12">
                                    <div class="status-coupon mright-10">
                                        <div class="status win" v-if="item.is_winner == 1">winner</div>
                                        <div class="status active" v-else-if="item.status == 'active'">{{item.status}}</div>
                                        <div class="status expired" v-else-if="item.status == 'expired'">{{item.status}}</div>
                                    </div>
                                </div>
                                <div class="coupon-column">
                                    <div class="invoice">
                                        <h5>No Invoice</h5>
                                        <p>Invoice</p>
                                        <br>
                                        <span>{{item.invoice}}</span>
                                    </div>
                                    <div class="product-name">
                                        <h5>Barang Rejeki</h5>
                                        <p>{{item.product}}</p>
                                    </div>
                                    <div class="purchasing-date">
                                        <h5>Tanggal Pembelian</h5>
                                        <p>{{item.order_date}}</p>
                                    </div>
                                    <div class="voucher-date">
                                        <h5>Tanggal Voucher</h5>
                                        <p>{{item.created}}</p>
                                    </div>
                                    <div class="coupon-number-user">
                                        <h5 class="white-nowrap">nomor kupon</h5>
                                        <div class="background-mobile-coupon">
                                            <p class="number-of-coupon">{{item.coupon_number}}</p>
                                            <p class="date-none fw-300 fs-11 mtop-7">{{format_date(item.order_date)}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <pagination-2 :total="pagination.total" :current="pagination.current" @changeAction="changeActionPagination($event)"></pagination-2>
                        </div>
                        <div class="mtop-20" v-else>
                            <blank-page :message="'Anda Belum Memiliki Kupon Rejeki Nomplok'" :image="'img/animation_empty_coupon.svg'"></blank-page>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal-hotpoint></modal-hotpoint>
    </div>
</template>

<script>
    import apiCustomer from '../../apis/Customer'
    import ProfileMenu from '../desktop/component/profile/ProfileMenu.vue'
    import ProfilePointInfo from '../desktop/component/profile/Profile.vue'
    import HighLightTwoColumn from './component/product/HighLightTwoColumn.vue'
    import HighLightImageOnly from './component/product/HighLightImageOnly.vue'
    import ModalHotpoint from './modal/ModalHotpoint.vue'
    import BlankPage from '../../components/desktop/BlankPage.vue'
    import RejekiNomplokBanner from './RejekiNomplokBanner.vue'
    import Pagination2 from '../../components/pagination/v2/pagination.vue'
    import moment from 'moment'

    export default {
        name: "KuponRejeki.vue",
        data(){
            return{
                profile : {},
                is_loading : true,
                pin : null,
                coupon : {},
                active_period : {},
                rejeki_banner : '',
                pagination : {
                    page : 1,
                    current : 1,
                    total : 1,
                    status : '',
                    date : '',
                    search : '',
                    filter : 'all',
                    perpage : 10
                }
            }
        },
        components : {
            ProfileMenu,
            ProfilePointInfo,
            HighLightTwoColumn,
            HighLightImageOnly,
            ModalHotpoint,
            BlankPage,
            RejekiNomplokBanner,
            Pagination2
        },
        mounted(){
            apiCustomer.profile().then( response => {
                this.profile = response.data
            });
            this.activePeriod()
            this.couponList()
            this.Banner()
            
        },
        methods: {
            Banner(){
                apiCustomer.Banners().then(response => {
                    this.rejeki_banner  = response.data.data.rejeki_banner
                })
            },
            couponList(){
                apiCustomer.couponList(this.pagination).then( response => {
                    this.is_loading = false
                    this.coupon = response.data.data.coupon
                    this.pagination.current = response.data.data.current_page
                    this.pagination.total = response.data.data.total
                });
            },
            format_date(date){
                if (date){
                    return moment(String(date)).format('DD/MM/YYYY')
                }
            },
            activePeriod(){
                apiCustomer.activePeriod().then( response => {
                    if(response.data.code == 200){
                        this.active_period = response.data.data
                    }
                });
            },
            changeActionPagination(event){
                if(event < 1 || event > this.pagination.total){
                    return false;
                }
                this.pagination.page = event
                this.couponList()
            }
        },
    }
</script>