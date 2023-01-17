<template>
    <div class="d-flex flex-column">
        <div class="row-hd rejeki-top-content">
            <div class="col-9 col-sm-12">
                <marquee direction="" onmouseover="this.stop();" onmouseout="this.start();">
                    <div class="text-container">
                        <a data-fancybox-group="gallery" class="fancybox" title="THE ELECTRIC LIGHTING ACT:section 35" v-if="text == null || text == ''">Tidak Ada Pemenang</a>
                        <a data-fancybox-group="gallery" class="fancybox" title="THE ELECTRIC LIGHTING ACT:section 35" v-else>{{ text }} <span class="fw-400">Total Hot Point {{total | NumberFormat}}</span></a>
                    </div>
                </marquee>
            </div>
            <div class="col-3 col-sm-12 ihsg">
                <div class="row-hd flex-column-mobile">
                    <div class="col-4 col-sm-12 flex-column ihsg-index">
                        <div class="row-hd ihsg-text">
                            <div class="row-hd">
                                <span class="ihsg">IHSG</span>
                                <h5 class="ihsg-number dsply-mobile">{{detail.ihsg}}<span class="unique-number">{{detail.last_ihsg}}</span></h5>
                            </div>
                            <img class="img-fd" alt="" src="img/ic_guide.svg">
                            <img class="img-fm" alt="" src="img/ic_purple_guide.svg">
                        </div>
                        <h2 class="ihsg-number dsply-desk">{{detail.ihsg}}<span class="unique-number">{{detail.last_ihsg}}</span></h2>
                    </div>
                    <div class="col-8 col-sm-12 ihsg-period">
                        <span class="fw-600">Periode {{detail.week}}</span>
                        <br>
                        <h5 class="fw-400 fs-11">{{periode}}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="mbottom-15">
            <rejeki-nomplok-banner-detail></rejeki-nomplok-banner-detail>
        </div>
        <div class="rejeki-mobile-menu d-none">
            <ul class="row-hd align-center justify-between">
                <li :class="route_active == 'rejeki-nomplok-info' ? 'active' :''" @click="route('info-rejeki-nomplok')">
                    <router-link class="nav-link flex-column justify-center" to="/info-rejeki-nomplok">
                        <div class="container-icon info"></div>
                        <span>Info Pemenang</span>
                    </router-link>
                </li>
                <li :class="route_active == 'rejeki-nomplok-about' ? 'active' :''" @click="route('about-rejeki-nomplok')">
                    <router-link class="nav-link flex-column justify-center" to="/rejeki-nomplok">
                        <div class="container-icon about"></div>
                        <span>Tentang Undian</span>
                    </router-link>
                </li>
                <li :class="route_active == 'rejeki-nomplok-product' ? 'active' :''" @click="route('product-rejeki-nomplok')">
                    <router-link class="nav-link flex-column justify-center" to="/product-rejeki-nomplok">
                        <div class="container-icon product"></div>
                        <span>Produk Rejeki</span>
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import RejekiNomplokBanner from '../../RejekiNomplokBanner.vue'
    import RejekiNomplokBannerDetail from './RejekiNomplokBannerDetail.vue'
    import apiCustomer from '../../../../apis/Customer'

    export default {
        name: "RejekiNomplokMenus.vue",
        data(){
            return{
                route_active: '',
                text: '',
                total: 0,
                periode: '',
                detail: {}
            }
        },
        methods: {
                route(route){
                this.route_active = route
            },
            current_week(){
                apiCustomer.currentWeek().then( response => {
                    this.detail = response.data.data
                    
                    this.text = response.data.data.winner
                    this.total = response.data.data.total_hotpoint
                    if(this.detail.start_month == this.detail.end_month){
                        this.periode = this.detail.start_date + ' - ' + this.detail.end_date + ' ' + this.detail.end_month + ' ' + this.detail.end_year
                    } else {
                        this.periode = this.detail.start_date + ' ' + this.detail.start_month + ' - ' + this.detail.end_date + ' ' + this.detail.end_month + ' ' + this.detail.end_year
                    }
                });
            }
        },
        mounted(){
            this.route_active = this.$route.name;
            this.current_week()
        },
        components : {
            RejekiNomplokBanner,
            RejekiNomplokBannerDetail
        }
    }
</script>