<template>
    <div class="list-product-rejeki">
        <h3 class="section-title dsply-desk">Katalog Rejeki Nomplok</h3>
        <div class="hd-filter">
            <img class="toggle-filter" src="/img/assets_hotdeal_filter.svg" alt="">
            <ul class="filter">
                <li v-bind:class="filter == 'new' ? 'active' : ''" @click="filterProduct('new')">Terbaru</li>
                <li v-bind:class="filter == 'low' ? 'active' : ''" @click="filterProduct('low')">Termurah</li> 
                <li v-bind:class="filter == 'high' ? 'active' : ''" @click="filterProduct('high')">Termahal</li>
                <!-- <li v-bind:class="filter == 'rating' ? 'active' : ''" @click="filterProduct(filter = 'rating')">Berdasarkan rating</li> -->
            </ul>
        </div>
        <div class="row-hd top-section-mobile dsply-mobile">
            <h3 class="section-title">Katalog Rejeki Nomplok</h3>
            <div class="filter">
                <img class="btn-hd-filter" src="img/assets_filter.svg" alt="">
                <div class="hd-slider-filter" id="show-filter">
                    <div class="close-div btn-close">
                        <img src="img/grey_bar.svg" alt="">
                    </div>
                    <div class="row-hd hd-filter-top">
                        <h4>Filter</h4>
                        <h5 @click="reset()">Reset</h5>
                    </div>
                    <ul>
                        <li id="filter-product" v-bind:class="filter == 'new' ? 'active' : ''" @click="filterProduct('new')">Terbaru</li>
                        <li id="filter-product" v-bind:class="filter == 'low' ? 'active' : ''" @click="filterProduct('low')">Termurah</li>
                        <li id="filter-product" v-bind:class="filter == 'high' ? 'active' : ''" @click="filterProduct('high')">Termahal</li>
                        <!-- <li v-bind:class="filter == 'rating' ? 'active' : ''" @click="filterProduct(filter = 'rating')">Berdasarkan rating</li> -->
                    </ul>
                </div>
            </div>
        </div>
        <div class="mbottom-30 mtop-20" v-if="is_loading">
            <card-skeleton :count="5"></card-skeleton>
            <div class="ph-mobile mtop-20">
                <div class="row-100 justify-between mleft-5">
                    <skeleton-product-with-detail :classes="'6'" v-for="(skeleton , i ) in 2 " :key="i"></skeleton-product-with-detail>
                </div>
                <div class="row-100 justify-between mleft-5">
                    <skeleton-product-with-detail :classes="'6'" v-for="(skeleton , i ) in 2 " :key="i"></skeleton-product-with-detail>
                </div>
            </div>
        </div>
        <div class="product-rejeki mtop-20" v-if="Object.keys(productlist).length > 0 ">
            <div class="card" v-for="(item , index) in productlist" :key="index">
                <div class="card-product">
                    <div class="container-img">
                        <router-link :to="'/product-detail/'+item.slug">
                            <img :src="item.galleries" :alt="item.name">    
                        </router-link>
                    </div>
                    <div class="detail-product">
                        <div class="rating">
                            <rating :rating="item.rating" :count_rating="item.review"></rating>
                        </div>
                        <div class="tooltip">
                            <router-link :to="'/product-detail/'+item.slug">
                                <h5 class="product-name">{{item.name}}</h5>
                            </router-link>
                            <span class="tooltiptext">{{item.name}}</span>
                        </div>
                        <s class="strike" v-if="item.discount_value > 0">{{item.price_before_discount}}</s>
                        <div class="price"> 
                            <h5 class="mright-5">{{item.label_face_price}}</h5>
                            <div class="discount" v-if="item.discount_value > 0">{{item.discount_value}}%</div>
                        </div>
                        <div class="tag mtop-10 mbottom-10">
                            <div class="free-shipping" v-if="item.shipping_fee_discount != null"></div>
                            <div class="voucher-available"></div>
                            <div class="badge-rejeki-nomplok"></div>
                        </div>
                        <div class="left-stock">
                            <div class="progress-border">
                                <div class="progress-bar" v-bind:style="{width : item.indikator.indicator + '%'}"></div>
                            </div>
                            <small class="leftover">Sisa {{item.stock}}</small>
                        </div>
                    </div>
                    <div class="vertical-btn">
                        <wish-component class="add-to-wish" :clases="item.in_wish_list ? 'active' : '' " :slug="item.slug"></wish-component>
                        <add-to-cart-small :product="item" @updateCarts="$emit('updateCarts' , $event)"></add-to-cart-small>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn-loadmore" v-if="btn_loadmore == true" @click="loadMore">lihat lebih banyak</button>
    </div>
</template>

<script>

    import apiCustomer from '../../apis/Customer'
    import AddToCartSmall from './component/AddToCartSmall.vue'
    import Rating from './component/Rating.vue'
    import WishComponent from './component/WishComponent.vue'
    import SkeletonProductWithDetail from '../loading/SkeletonProductWithDetail.vue'
    import CardSkeleton from '../skeleton/CardSkeleton.vue'

    export default {
        name: "RejekiNomplokListProduct.vue",
        data(){
            return{
                productlist : {},
                page : 1,
                btn_loadmore : true,
                is_loading : true,
                filter: 'new',
            }
        },
        components : {
                Rating,
                AddToCartSmall,
                WishComponent,
                SkeletonProductWithDetail,
                CardSkeleton
        },
        mounted (){
            this.productList()
        },
        methods: {
            productList(){
                apiCustomer.productList(this.page, this.filter).then( response => {
                    if(this.page == 1){
                        this.productlist = response.data.data.list
                    }else{
                        this.productlist.push.apply( this.productlist ,response.data.data.list)
                    }
                    if(Object.keys(response.data.data.list).length < 10){
                        this.btn_loadmore = false
                    }
                    this.is_loading = false
                    $("#modal_load").fadeOut();
                });
            },
            filterProduct(val){
                // $("#modal_load").fadeIn();
                // const fd = new FormData
                // fd.append('filter', this.filter)
                // apiCustomer.filterRejekiNomplok(fd).then( response => {
                //     if(response.data.code === 200){
                //         this.productlist = []
                //         this.productlist.push.apply( this.productlist ,response.data.data.list)
                //         this.btn_loadmore = false
                //         $("#modal_load").fadeOut();
                //     }
                //     $(".btn-close").click();
                // })
                $("#modal_load").fadeIn();
                this.filter = val;
                this.productList();
            },
            loadMore(){
                $("#modal_load").fadeIn();
                this.page++;
                this.productList();
            },
            reset(){
                $("#modal_load").fadeIn();
                $("#filter-product").removeClass("active");
                this.productList();
            }
        },
    }

</script>