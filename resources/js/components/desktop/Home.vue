<template>
    <div>
        <div class="mbottom-10" v-if="is_loading">
            <banner-skeleton :count="1"></banner-skeleton>
        </div>
        <div v-if="!is_loading">
            <div class="home-banner" v-if="Object.keys(banners).length > 0">
                <VueSlickCarousel class="slider" :arrows="true" :dots="true" :autoplay="true" :autoplaySpeed = "5000">
                    <div v-for="(item , index) in banners" :key="index">
                        <div v-if="item.video_url != null && item.video_url != '' ">
                            <a v-if="item.new_tab != true"  :href="item.url" v-bind:target="item.new_tab == true ? '_blank' : ''" >
                                <img  :src="item.img_url" alt="">
                                <!-- <img v-if="!isMobile()" :src="item.img_url" alt="">
                                <img v-else :src="item.img_url_mobile" alt=""> -->
                            </a>
                            <a v-if="item.new_tab == true" :href="item.url" target="_blank"><img :src="item.img_url" alt=""></a>
                            <!-- <button type="button" class="btn-light" href="javascript:void(0)" @click="modal_video(item)"><img src="img/ic_play.svg" alt="">Tonton Sekarang</button> -->
                        </div>
                        <div  v-else>
                            <a v-if="item.new_tab != true" :href="item.url" v-bind:target="item.new_tab == true ? '_blank' : ''">
                                <img  :src="item.img_url" alt="">
                                <!-- <img v-if="!isMobile()" :src="item.img_url" alt="">
                                <img v-else :src="item.img_url_mobile" alt=""> -->
                            </a>
                            <!-- <a v-if="item.new_tab == true" :href="item.url"  target="_blank"><img :src="item.img_url" alt=""></a> -->
                            <a v-if="item.new_tab == true" :href="item.url"  target="_blank"><img src="/img/banner_home.svg" alt=""></a>
                        </div>
                    </div>
                </VueSlickCarousel>
                <!-- <modal-video-product :product="show_item" @stop_video="stop_video()"> </modal-video-product> -->
            </div>
        </div>

       <categories-menu></categories-menu>
       
       <highliht-product></highliht-product>

        <!-- <div class="row">
            <div class="mbottom-10" v-if="is_loading">
                <banner-skeleton :count="1"></banner-skeleton>
            </div>
            <div class="banner-section" v-if="!is_loading">
                <router-link to="/news-detail/keramat-(keranjang-pasti-hemat)-special-treat-halloween">
                    <img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/upload/files/6358935895ee2-files1666749272.png" alt="">
                </router-link>
            </div>
        </div> -->

        <FlashSale @updateCarts="$emit('updateCarts' , $event)"></FlashSale>

        <!-- <FlashEvent @updateCarts="$emit('updateCarts' , $event)"></FlashEvent> -->

        <newest-product @updateCarts="$emit('updateCarts' , $event)"></newest-product>

        <high-light-video-product @updateCarts="$emit('updateCarts' , $event)"></high-light-video-product>

        <div class="banner-nomplok-home">
            <rejeki-nomplok-banner></rejeki-nomplok-banner>
        </div>

        <high-light-image-only @updateCarts="$emit('updateCarts' , $event)"></high-light-image-only>

        <wishlist-product @updateCarts="$emit('updateCarts' , $event)"></wishlist-product>

        <high-light-two-column @updateCarts="$emit('updateCarts' , $event)"></high-light-two-column>

        <newest-card-product @updateCarts="$emit('updateCarts' , $event)"></newest-card-product>

        <div id="ob_list_benefit" class="mbottom-30" v-if="is_loading">
            <div class="ph-desktop">
                <div class="row justify-between">
                    <div class="d-block ph-col-2">
                        <div class="mright-10 bg-placeholder ph-h100 rounded"></div>
                    </div>
                    <div class="d-block ph-col-2">
                        <div class="mright-10 bg-placeholder ph-h100 rounded"></div>
                    </div>
                    <div class="d-block ph-col-2">
                        <div class="mright-10 bg-placeholder ph-h100 rounded"></div>
                    </div>
                    <div class="d-block ph-col-2">
                        <div class="mright-10 bg-placeholder ph-h100 rounded"></div>
                    </div>
                    <div class="d-block ph-col-2">
                        <div class="mright-10 bg-placeholder ph-h100 rounded"></div>
                    </div>
                    <div class="d-block ph-col-2">
                        <div class="bg-placeholder ph-h100  rounded"></div>
                    </div>
                </div>
            </div>
            <div class="ph-mobile">
                <div class="row justify-between">
                    <div class="d-block ph-col-4">
                        <div class="mright-10 bg-placeholder ph-h100 rounded"></div>
                    </div>
                    <div class="d-block ph-col-4">
                        <div class="mright-10 bg-placeholder ph-h100 rounded"></div>
                    </div>
                    <div class="d-block ph-col-4">
                        <div class="bg-placeholder ph-h100 rounded"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mbottom-30" v-if="footer == true && Object.keys(strengths).length > 0">
            <ul class="list-benefit">
                <li v-for="(item , index) in strengths" :key="index">
                    <div>
                        <img :src="item.image" alt="" width="100%">
                        <h5>{{ item.title }}</h5>
                        <h6>{{ item.description }}</h6>
                    </div>
                </li>
            </ul>
        </div>
        <modal-video-banner :dataurl="modal_banner_url" @close_modal_video="update_modal($event)"></modal-video-banner>


    </div>
</template>

<script>

    import SmallCardProduct from './component/product/SmallCardProduct.vue'
    import VueSlickCarousel from 'vue-slick-carousel'
    import 'vue-slick-carousel/dist/vue-slick-carousel.css'
    import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
    import ModalVideoProduct from './ModalVideoProduct.vue'
    import HighlihtProduct from './component/product/HighlightProduct.vue'
    import FlashSale from './component/product/FLashSale.vue'
    import FlashEvent from './component/product/FLashEvent.vue'
    import HighLightTwoColumn from './component/product/HighLightTwoColumn.vue'
    import HighLightImageOnly from './component/product/HighLightImageOnly.vue'
    import HighLightVideoProduct from './component/product/HighlightVideoProduct.vue'
    import apiCustomer from '../../apis/Customer'
    import RejekiNomplokBanner from './RejekiNomplokBanner.vue'
    import ModalVideoBanner from './modal/ModalVideoBanner.vue'
    import Vue from 'vue'
    import VueCookies from 'vue-cookies'
    import NewProduct from './NewProduct.vue'
    import WishlistProduct from './WishlistProduct.vue'
    import NewestProduct from './NewestProduct.vue'
    import CategoriesMenu from './component/CategoriesMenu.vue'
    import BannerSkeleton from '../skeleton/BannerSkeleton.vue'
    import NewestCardProduct from './component/product/NewestCardProduct.vue'

    Vue.use(VueCookies)

    export default {
        name: "Home.vue",
        data(){
            return {
                footer: true,
                banners : {},
                product : {},
                rejeki_banner : '',
                is_loading : true,
                show_item : {},
                modal_banner_url : {},
                strengths : {}
            }
        },
        created: function () {
            this.footer =  this.$route.name == 'login' ? false: true;
        },
        created(){
            if(this.$route.query.utm_id){
                Vue.$cookies.set('utm_id', this.$route.query.utm_id, '30d')
            }
            if(this.$route.query.utm_source){
                Vue.$cookies.set('utm_source', this.$route.query.utm_source, '30d')
            }
            if(this.$route.query.utm_campaign){
                Vue.$cookies.set('utm_campaign', this.$route.query.utm_campaign, '30d')
            }
            if(this.$route.query.utm_term){
                Vue.$cookies.set('utm_term', this.$route.query.utm_term, '30d')
            }
            if(this.$route.query.utm_medium){
                Vue.$cookies.set('utm_medium', this.$route.query.utm_medium, '30d')
            }
        },
        mounted(){
            this.BannerHome();
            this.get_strengths();
        },
        methods: {
            get_strengths() {
                 apiCustomer.strengths().then(response => {
                        this.strengths = response.data.data;
                        this.is_loading = false;
                        this.intersected = true;
                        // this.observer.disconnect();
                    })
                // const element = document.querySelector("#ob_list_benefit");
                // this.observer = new IntersectionObserver(entries => {
                //     const target = entries[0];
                //     if (target.isIntersecting) {
                       
                //     }
                // });
                // this.observer.observe(element);
            },
            BannerHome(){
                apiCustomer.Banners().then(response => {
                    this.banners = response.data.data.banner;
                    this.rejeki_banner  = response.data.data.rejeki_banner
                    this.is_loading = false;
                })
            },
            modal_video(item){
                this.modal_banner_url = {cover : item.img_url , video : item.video_url}
                $("#modal_banner_video").fadeIn(function () {
                    $("#modal_banner_video").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_video(item){
                this.show_item = item;
                $("#video_produk_modal").fadeIn(function () {
                    $("#video_produk_modal").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            stop_video(){
               this.show_item = {};
               $('#video_produk_modal').fadeOut();
            },
            openForm(form){
                this.auth_active = form;
                document.getElementById("myOverlay").style.display="block";
                document.querySelector('body').style.overflow = "hidden";
            },
            closeForm(){
                this.error = {};
                document.getElementById("myOverlay").style.display="none";
                document.querySelector('body').style.overflow = "auto";
            },
            update_modal(event){
                
                this.modal_banner_url = event
            },
            login(){
                this.error = {};
                User.login(this.form_login).then(response => {
                    if (response.data.code === 200){
                        localStorage.setItem('auth','true');
                        this.form_login = {};
                        User.profile().then(response =>{
                            this.is_login = true;
                            this.closeForm();
                        })
                    }else{
                        this.error = response.data.message;
                    }
                }).catch((errors) => {
                    this.error = errors.response.data.errors;
                });
            },
            isMobile() {
                if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    return true
                } else {
                    return false
                }
            },
        },
        components:{
            VueSlickCarousel,
            HighlihtProduct,
            FlashSale,
            FlashEvent,
            HighLightTwoColumn,
            HighLightImageOnly,
            HighLightVideoProduct,
            ModalVideoProduct,
            RejekiNomplokBanner,
            ModalVideoBanner,
            NewProduct,
            WishlistProduct,
            CategoriesMenu,
            BannerSkeleton,
            NewestCardProduct,
            NewestProduct
        },
    }
</script>
