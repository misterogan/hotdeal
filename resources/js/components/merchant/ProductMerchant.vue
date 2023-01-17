<template>
    <div class="col-80p w-80">
        <div class="row-100" >
            <div class="col-12" >
                <div class="top-content-seller-page mbottom-15">
                    <div class="container-img">
                        <img :src="vendor.image" alt="">
                    </div>
                    <div class="merchant name">
                        {{vendor.name}}
                        <div class="sub-merchant">
                            <img src="/img/assets_location_small.svg" alt="">
                            <span class="fw-400">{{vendor.city.name}}</span>
                        </div>
                    </div>
                    <div class="merchant rating">
                        penilaian
                        <div class="sub-merchant">
                            <img src="/img/assets_rating_merchant.svg" alt="">
                            {{ rating }} &nbsp;({{ total_review }})
                        </div>
                    </div>
                    <div class="merchant review">
                        produk terjual
                        <div class="sub-merchant">
                            <img src="/img/icon_product_sold.svg" alt="">
                            {{ purchase }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mbottom-10" v-if="load_banner">
            <banner-skeleton-fluid :count="1"></banner-skeleton-fluid>
        </div>
        <div class="merchant-carousel">
            <VueSlickCarousel class="slider" :arrows="true" :dots="true" :autoplay="true" :autoplaySpeed = "5000" v-if="Object.keys(merchant_banner).length > 0">
                <div v-for="(item, index) in merchant_banner" :key="index">
                    <img :src="item.img_url" v-if="item.new_tab === false">
                    <a :href="item.url" :target="item.new_tab == true ? '_blank' : ''" v-else>
                        <img :src="item.img_url" alt="">
                    </a>
                </div>
            </VueSlickCarousel>
        </div>
        <div class="row-100" v-if="Object.keys(merchant_product).length > 0">
            <h3 class="title-section mbottom-10">produk menarik perhatianmu toko ini</h3>
        </div> 
        <div class="row-100" id="ob_highlight_product_merchant">
            <div class="col-12">
                <div class="mbottom-30" v-if="is_loading">
                    <card-skeleton :count="5"></card-skeleton>
                </div>
                <div class="section-product highlight-merchant" v-if="Object.keys(merchant_product).length > 0">
                    <small-card-product-without-stock :item="item" v-for="(item , index) in merchant_product"  :key="index" @updateCarts="$emit('updateCarts' , $event)"></small-card-product-without-stock>
                </div>
            </div>
        </div>
        <div class="row-100">
            <div class="filter merchant mbottom-20">
                <img src="/img/assets_hotdeal_filter.svg" alt="">
                <ul>
                    <li v-bind:class="sort == '' ? 'active' : ''" @click="merchantProduct('')">semua produk</li>
                    <li v-bind:class="sort == 'high' ? 'active' : 'high'" @click="merchantProduct('high')">harga tertinggi</li>
                    <li v-bind:class="sort == 'low' ? 'active' : 'low'" @click="merchantProduct('low')">harga terendah</li>
                </ul>
            </div> 
        </div>
        <div class="row-100" id="ob_highlight_product_merchant">
            <div class="col-12">
                <div class="mbottom-30" v-if="is_loading || is_searching">
                    <card-skeleton :count="5"></card-skeleton>
                </div>
                <div class="section-product highlight-merchant" v-if="Object.keys(productlist).length > 0">
                    <small-card-product-without-stock :item="item" v-if="!is_searching" v-for="(item , index) in productlist"  :key="index" @updateCarts="$emit('updateCarts' , $event)"></small-card-product-without-stock>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import VueSlickCarousel from 'vue-slick-carousel'
    import 'vue-slick-carousel/dist/vue-slick-carousel.css'
    import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
    import SmallCardProductWithoutStock from '../desktop/component/product/SmallCardProductWithoutStock.vue'
    import apiCustomer from '../../apis/Customer'
    import Rating from '../desktop/component/Rating.vue'
    import Location from '../../components/desktop/component/filter/Location.vue'
    import Category from '../../components/desktop/component/filter/Category.vue'
    import SkeletonProductWithDetail from '../loading/SkeletonProductWithDetail.vue'
    import CardSkeleton from '../skeleton/CardSkeleton.vue'
    import BannerSkeletonFluid from '../skeleton/BannerSkeletonFluid.vue'
    export default {
        name: "ProductMerchant.vue",
        data(){
            return {
                recomendation : {},
                merchant_product : {},
                merchant_banner : {},
                productlist : {},
                vendor : {
                    name : '',
                    city : {}
                },
                filter : {
                    province_id : null,
                    min_price : 0,
                    max_price : 0,
                    category_id : 0,
                    review : null,
                    brand_id : null,
                    keyword : '',
                    selected_filter : [],
                    location_data : {},
                    category_data : {},
                },
                route_active : '',
                vendor_id : '',
                total_review : '',
                rating : '',
                purchase : '',
                is_loading : true,
                is_searching : true,
                load_banner : true,
                sort: ''
            }
        },
        mounted(){
            this.merchantProduct();
            this.merchantHighlight();
            this.merchantBanner();
            this.route_active = this.$route.name;
            this.vendor_id = this.$route.params.pathMatch//this.$route.query.merchant;
        },
        methods: {
            merchantBanner(){
                this.load_banner = true
                var vendor_id = this.$route.params.pathMatch//this.$route.query.merchant
                const fd = new FormData();
                fd.append('vendor_id' ,vendor_id)
                const element = document.querySelector("#ob_highlight_product_merchant");
                this.observer = new IntersectionObserver(entries => {
                    const target = entries[0]; 
                    if (target.isIntersecting) {
                        apiCustomer.merchantBanner(fd).then( response => {
                            this.merchant_banner = response.data.data;
                            this.load_banner=false;
                            this.intersected = true;
                            this.observer.disconnect();
                        })
                    }
                });
                this.observer.observe(element);
            },
            merchantHighlight(){
                this.is_loading = true
                var vendor_id = this.$route.params.pathMatch//this.$route.query.merchant
                const fd = new FormData();
                fd.append('vendor_id' ,vendor_id)
                const element = document.querySelector("#ob_highlight_product_merchant");
                this.observer = new IntersectionObserver(entries => {
                    const target = entries[0]; 
                    if (target.isIntersecting) {
                        apiCustomer.merchantHighlightProduct(fd).then( response => {
                            this.merchant_product = response.data.data.products;
                            this.intersected = true;
                            this.observer.disconnect();
                            this.is_loading = false;
                        })
                    }
                });
                this.observer.observe(element);
            },
            merchantProduct(filter){
                this.sort = filter
                this.is_searching = true
                var vendor_id = this.$route.params.pathMatch //this.$route.query.merchant
                const fd = new FormData();
                fd.append('vendor_id' ,vendor_id)
                fd.append('filter' ,filter)
                const element = document.querySelector("#ob_highlight_product_merchant");
                this.observer = new IntersectionObserver(entries => {
                    const target = entries[0]; 
                    if (target.isIntersecting) {
                        apiCustomer.merchantProduct(fd).then( response => {
                            this.productlist = response.data.data.products;
                            this.vendor = response.data.data.vendor;
                            this.total_review = response.data.data.review.total_review
                            this.rating = response.data.data.review.rating
                            this.purchase = response.data.data.purchase
                            this.is_searching = false
                            this.intersected = true;
                            this.observer.disconnect();
                        })
                    }
                });
                this.observer.observe(element);
            },
            route(route){
                this.route_active = route
            }
        },
        components:{
            VueSlickCarousel,
            SmallCardProductWithoutStock,
            Location,
            Category,
            SkeletonProductWithDetail,
            CardSkeleton,
            BannerSkeletonFluid
        },
    }
</script>