<template>
    <div class="col">
        <div id="ob_product_wishlist" class="mbottom-30" v-if="is_loading">
            <div class="ph-desktop ph-container">
                <card-skeleton :count="6"></card-skeleton>
            </div>
            <div class="ph-mobile ph-container">
                <card-skeleton :count="2"></card-skeleton>
            </div>
        </div>
        <div v-if="!is_loading && Object.keys(wishlist).length > 0">
            <div class="row align-center mbottom-15">
                <h3 class="title-section">Wishlist Kamu</h3>
                <router-link class="see-all" to="/wishlist">Lihat Semua</router-link>
            </div>
            <div class="section-product wishlist">
                <small-card-product-without-stock-highlight :item="item" v-for="(item , index) in wishlist"  :key="index" @updateCarts="$emit('updateCarts' , $event)"></small-card-product-without-stock-highlight>
            </div>
        </div>
    </div>
</template>

<script>
    import apiCustomer from '../../apis/Customer'
    import SkeletonProductWithDetail from '../loading/SkeletonProductWithDetail.vue'
    import SmallCardProductWithoutStockHighlight from './component/product/SmallCardProductWithoutStockHighlight.vue'
    import Rating from './component/Rating.vue'
    import CardSkeleton from '../skeleton/CardSkeleton.vue'

    export default {
        data(){
            return {
                is_loading : true,
                wishlist : {},
                observer: null,
                intersected: false
            }
        },
        name: "WishlistProduct.vue",
        mounted(){
            this.recomendationProduct()
        },
        methods: {
            recomendationProduct(){
                const element = document.querySelector("#ob_product_wishlist");
                this.observer = new IntersectionObserver(entries => {
                    const target = entries[0];
                    if (target.isIntersecting) {
                         apiCustomer.highlightWishlist().then( response => {
                             this.wishlist = response.data.data;
                            this.is_loading=false;
                            this.intersected = true;
                            this.observer.disconnect();
                        })
                    }
                });
                this.observer.observe(element);
            }
        },
        components:{
            Rating,
            SmallCardProductWithoutStockHighlight,
            SkeletonProductWithDetail,
            CardSkeleton
        },
    }
</script>
                