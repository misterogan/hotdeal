<template>
    <div class="col-80p">
        <div class="box-rounded-shadow">
            <h3 class="title-section mbottom-10">Ulasan</h3>
            <div class="filter merchant mbottom-20">
                <img src="/img/assets_hotdeal_filter.svg" alt="">
                <ul>
                    <li @click="getReview('')" v-bind:class="filter_active === '' ? 'active' : ''">semua ulasan</li>  
                    <li @click="getReview('new')" v-bind:class="filter_active === 'new' ? 'active' : ''">terbaru</li>
                    <li @click="getReview('old')" v-bind:class="filter_active === 'old' ? 'active' : ''">terlama</li>
                </ul>
            </div>
            <div v-if="is_loading">
                <div class="content-modal">
                    <!-- <large-skeleton :count="1"></large-skeleton> -->
                    <list-skeleton :count="3"></list-skeleton>
                </div>
            </div>
            <div class="row-100 flex-column" v-if="!is_loading">
                <div v-if="Object.keys(reviews).length > 0">
                    <div class="merchant-review-column" v-for="(item , index) in reviews" :key="index">
                        <div class="review-date mbottom-10">
                            {{ item.created_at }}
                            <div class="dot-red mleft-10" v-if="item.is_new === true"><span>Baru</span></div>
                        </div>
                        <div class="review-comment">
                            <div class="container-img">
                                <img v-if="item.user.image" :src="item.user.image" alt="">
                                <img v-else src="/img/dummy_profile.svg" alt="">
                            </div>
                            <div class="container-review">
                                <div class="d-flex align-center mbottom-5">
                                    <div class="mright-10">
                                        {{item.product.name}}
                                    </div>
                                    <div class="review-product-rating">
                                        <ul class="rating">
                                            <li v-for="i in 5" :key="i">
                                                <img :src="i <= item.rating ? '/img/active_rating.svg' : '/img/inactive_rating.svg'" alt="">
                                            </li>
                                        </ul>
                                        {{ item.rating }}.0
                                    </div>
                                </div>
                                <div class="mbottom-5" v-if="item.review != ''">
                                    {{ item.review }}
                                </div>
                                <div class="mbottom-5">
                                    Diulas oleh : <b>{{ item.user.name }}</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mtop-20" v-else>
                    <blank-page :message="'Belum ada review'" :image="'img/animation_empty_hotpoint.svg'"></blank-page>
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
    import BlankPage from '../desktop/BlankPage.vue'
    import ListSkeleton from '../skeleton/ListSkeleton.vue'


    export default {
        name: "ReviewMerchant.vue",
        data(){
            return {
                recomendation : {},
                reviews : {},
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
                is_loading: true,
                filter_active: ''
            }
        },
        mounted(){
            this.route_active = this.$route.name;
            this.vendor_id = this.$route.params.pathMatch//this.$route.query.merchant;
            this.getReview('');
        },
        methods: {
            route(route){
                this.route_active = route
            },
            getReview(filter) {
                var vendor_id = this.$route.params.pathMatch//this.$route.query.merchant;
                const fd = new FormData();
                fd.append('vendor_id' ,vendor_id)
                fd.append('filter' ,filter != undefined ? filter : '')
                apiCustomer.merchantReview(fd).then( response => {
                    if(response.data.code === 200) {
                        this.reviews = response.data.data.reviews
                        this.vendor_id = vendor_id
                        this.is_loading = false
                    }
                })
                this.filter_active = filter
            },
        },
        components:{
            VueSlickCarousel,
            SmallCardProductWithoutStock,
            Location,
            Category,
            BlankPage,
            ListSkeleton
        },
    }
</script>