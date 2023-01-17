<template>
    <div>
        <div class="row col2-flex merchant">
            <div class="col-20p">
                <div class="box-filter-purple">
                    <div class="menu-dropdown">
                        <h4>Hot Seller</h4>
                        <img class="logo_dropdown" src="/img/ic_dropdown_profile.svg" alt="">
                    </div>
                    <ul class="sub-menu-mobile">
                        <li :class="page == 'about' ? 'active' :''" @click="page='about'">
                            <router-link class="btn-link" :to="'/merchant/'+merchant+'?p=about'">
                            tentang seller
                            </router-link>
                        </li>
                        <li :class="page == 'products' ? 'active' :''" @click="page='products'">
                            <router-link class="btn-link" :to="'/merchant/'+merchant+'?p=products'">
                            produk
                        </router-link>
                        </li>
                        <li :class="page == 'reviews' ? 'active' :''" @click="page='reviews'">
                            <router-link class="btn-link" :to="'/merchant/'+merchant+'?p=reviews'">
                            ulasan
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
            <ProductMerchant v-if="page == 'products'"></ProductMerchant>
            <ReviewMerchant v-if="page == 'reviews'"></ReviewMerchant>
            <DetailMerchant v-if="page == 'about'"></DetailMerchant>
        </div>
    </div>
</template>
<script>
    import ProductMerchant from './ProductMerchant.vue'
    import ReviewMerchant from './ReviewMerchant.vue'
    import DetailMerchant from './DetailMerchant.vue'

    export default {
        name: "Merchant.vue",
        data(){
            return {
                merchant : '',
                page : 'products',
                route_active : 'products'
            }
        },
        mounted(){
            this.merchant = this.$route.params.pathMatch
            this.page = this.$route.query.p ? this.$route.query.p : 'products'
        },
        components : {
            ProductMerchant,
            ReviewMerchant,
            DetailMerchant
        }
    }
</script>