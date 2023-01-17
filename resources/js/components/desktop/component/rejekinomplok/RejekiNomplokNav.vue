<template>
    <div class="hd-nav-purple">
        <div class="title">Rejeki Nomplok</div>
        <ul>
            <li :class="route_active == 'rejeki-nomplok-about' ? 'active' :''" @click="route('about-rejeki-nomplok')">
                <router-link class="nav-link" to="/rejeki-nomplok">
                    <img src="/img/nav_about_rejeki.svg" alt="">
                    <span>Tentang Undian</span>
                </router-link>
            </li>
            <li :class="route_active == 'rejeki-nomplok-info' ? 'active' :''" @click="route('info-rejeki-nomplok')">
                <router-link class="nav-link" to="/info-rejeki-nomplok">
                    <img src="/img/nav_info_rejeki.svg" alt="">
                    <span>Info Pemenang</span>
                </router-link>
            </li>
            <li :class="route_active == 'rejeki-nomplok-product' ? 'active' :''" @click="route('product-rejeki-nomplok')">
                <router-link class="nav-link" to="/product-rejeki-nomplok">
                    <img src="/img/nav_product_rejeki.svg" alt="">
                    <span>Produk Rejeki</span>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
    import RejekiNomplokBanner from '../../RejekiNomplokBanner.vue'
    import apiCustomer from '../../../../apis/Customer'

    export default {
        name: "RejekiNomplokNav.vue",
        data(){
            return{
                route_active: '',
                text: '',
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
            RejekiNomplokBanner
        }
    }
</script>