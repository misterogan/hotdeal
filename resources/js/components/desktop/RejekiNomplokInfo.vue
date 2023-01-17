<template>
    <div class="row-hd hd-rejeki-nomplok">
        <div class="col-csp20-2">
            <rejeki-nomplok-nav></rejeki-nomplok-nav>
        </div>
        <div class="col-csm40-10 col-sm-12">
            <rejeki-nomplok-menus></rejeki-nomplok-menus>
            <div class="row-hd flex-column rejeki-winner-section">
                <h3 class="section-title">List Pemenang</h3>
                <div class="hd-filter">
                    <img class="toggle-filter" src="/img/assets_hotdeal_filter.svg" alt="">
                    <ul class="filter">
                        <li v-bind:class="pagination.filter == 'active' ? 'active' : ''" @click="weekList(pagination.filter = 'active')">Periode Aktif</li>
                        <li v-bind:class="pagination.filter == 'high' ? 'active' : ''" @click="weekList(pagination.filter = 'high')">Hot Point Tertinggi</li>
                        <li v-bind:class="pagination.filter == 'low' ? 'active' : ''" @click="weekList(pagination.filter = 'low')">Hot Point Terendah</li>
                    </ul>
                </div>
                <div class="rejeki-winner-list">
                    <div class="rejeki-card-winner active">
                        <div class="row-hd align-center rejeki-top-card">
                            <img class="img-fm" width="28" src="img/ic_rejeki_big.svg" alt="">
                            <div class="d-flex flex-column">
                                <span>Gift Hot Point</span>
                                <b>{{total_point | NumberFormat}}</b>
                            </div>
                        </div>
                        <div class="row-hd flex-column rejeki-bottom-card">
                            <!-- <img class="big-icon-bg" src="/img/ic_rejeki_big.svg" alt=""> -->
                            <h5>Periode {{detail.week}}</h5>
                            <span>{{periode}}</span>
                            <button class="btn-primary __custom-rejeki" @click="show_winner_list(detail.week)" v-if="!isMobile()">
                                Lihat Pemenang 
                            </button>
                            <button class="btn-primary __custom-rejeki" v-else>
                                <router-link :to="'/winner-rejeki-nomplok?week='+detail.week">Lihat Pemenang</router-link>
                            </button>
                        </div>
                    </div>
                    <div class="dsply-mobile rejeki-period-filter row-hd mbottom-5">
                        <h4 class="col-sm-9 white-nowrap fs-black">Periode Sebelumnya</h4>
                        <div class="col-sm-3 filter">
                            <div class=" row-hd justify-right btn-hd-filter">
                                <img width="15 mright-5" src="img/assets_filter.svg" alt="">
                                <h5>Filter</h5>
                            </div>
                            <div class="hd-slider-filter">
                                <div class="close-div btn-close">
                                    <img src="img/grey_bar.svg" alt="">
                                </div>
                                <div class="row-hd hd-filter-top">
                                    <h4>Filter</h4>
                                    <h5 @click="weekList(pagination.filter = 'active')">Reset</h5>
                                </div>
                                <ul>
                                    <li v-bind:class="pagination.filter == 'active' ? 'active' : ''" @click="weekList(pagination.filter = 'active')">Periode Aktif</li>
                                    <li v-bind:class="pagination.filter == 'high' ? 'active' : ''" @click="weekList(pagination.filter = 'high')">Hot Point Tertinggi</li>
                                    <li v-bind:class="pagination.filter == 'low' ? 'active' : ''" @click="weekList(pagination.filter = 'low')">Hot Point Terendah</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- <div  v-if="Object.keys(week).length > 0 "> -->
                        <div class="rejeki-card-winner expired"  v-if="Object.keys(week).length > 0 " v-for="(item, index) in week" :key="index">
                            <div class="row-hd rejeki-top-card">
                                <img class="ic_expired_rejeki" src="/img/ic_rejeki_big.svg" alt="">
                                <div class="d-flex flex-column">
                                    <span>Gift Hot Point</span>
                                    <b>{{item.total_point | NumberFormat}}</b>
                                </div>
                            </div>
                            <div class="row-hd flex-column rejeki-bottom-card">
                                <div class="row-hd flex-column">
                                    <h5>Periode {{item.week}}</h5>
                                    <span>  {{item.periode}}</span>
                                </div>
                                <button class="btn-primary __custom-rejeki" @click="show_winner_list(item.week)" v-if="!isMobile()">
                                    Lihat Pemenang 
                                </button>
                                <button class="btn-primary __custom-rejeki" v-else>
                                    <router-link :to="'/winner-rejeki-nomplok?week='+item.week">Lihat Pemenang</router-link>
                                </button>
                            </div>
                        </div>
                    <!-- </div> -->
                    <button class="btn-primary __custom-see-all">Lihat Semua periode</button>
                </div>
            </div>
        </div>
        <modal-pemenang-rejeki :data="byweek" :winners="winners" :pagination="pagination"></modal-pemenang-rejeki>
    </div>
</template>

<script>
    import RejekiNomplokBanner from './RejekiNomplokBanner.vue'
    import apiCustomer from '../../apis/Customer'
    import RejekiNomplokNav from '././component/rejekinomplok/RejekiNomplokNav.vue'
    import RejekiNomplokMenus from '././component/rejekinomplok/RejekiNomplokMenus.vue'
    import ModalPemenangRejeki from './modal/ModalPemenangRejeki.vue'


    export default {
        name: "RejekiNomplokInfo.vue",
        data(){
            return{
                route_active: '',
                text: '',
                periode: '',
                detail: {},
                week: {},
                byweek: {},
                winners: {},
                pagination : {
                    page : 1,
                    current : 1,
                    total : 1,
                    filter : 'active'
                },
                total_point: 0,
            }
        },
        methods: {
                route(route){
                this.route_active = route
            },
            isMobile() {
                if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    return true
                } else {
                    return false
                }
            },
            show_winner_list(week, page){
                apiCustomer.winner({week:week, page: page}).then( response => {
                    this.byweek = response.data.data.week
                    this.winners = response.data.data.winner.data
                    this.pagination.total = response.data.data.total_page
                    this.pagination.current = response.data.data.current_page
                    $("#list_winner_rejeki").fadeIn(function() {
                        $("body").addClass('overflow-hidden');
                    })
                })
            },
            current_week(){
                apiCustomer.currentWeek().then( response => {
                    this.detail = response.data.data
                    if(this.detail.start_month == this.detail.end_month){
                        this.periode = this.detail.start_date + ' - ' + this.detail.end_date + ' ' + this.detail.end_month + ' ' + this.detail.end_year
                    } else {
                        this.periode = this.detail.start_date + ' ' + this.detail.start_month + ' - ' + this.detail.end_date + ' ' + this.detail.end_month + ' ' + this.detail.end_year
                    }
                    this.total_point = this.detail.total_point
                })
            },
            weekList(){
                apiCustomer.weekList(this.pagination).then( response => {
                    this.week = response.data.data.week
                });
                $(".btn-close").click();
            },
        },
        mounted(){
            this.route_active = this.$route.name;
            this.current_week()
            this.weekList()
            this.isMobile()
        },
        components : {
            RejekiNomplokBanner,
            ModalPemenangRejeki,
            RejekiNomplokNav,
            RejekiNomplokMenus
        }
    }
</script>