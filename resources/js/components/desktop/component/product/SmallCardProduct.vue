<template>
    <div class="card">
        <div class="card-product">
            <router-link :to="'/product-detail/'+item.slug">
                <div class="container-img">
                    <img :src="item.galleries" :alt="item.name">
                </div>
                <div class="detail-product">
                    <div class="product-info">
                        <rating class="mbottom-5" :rating="item.rating" :count_rating="item.review" :sold="item.sold"></rating>
                        <div class="tooltip">
                            <h5 class="product-name">{{item.name}}</h5>
                            <span class="tooltiptext">{{item.name}}</span>
                        </div>
                        <s class="strike mright-5" v-if="item.discount_value > 0">{{item.price_before_discount}}</s>
                        <div class="price mbottom-5 mtop-3">
                            <h5 class="mright-5">{{item.label_face_price}}</h5>
                            <div class="discount" v-if="item.discount_value > 0">{{item.discount_value}}%</div>
                        </div>
                    </div>
                    <div class="tag mbottom-5 mtop-5">
                        <!-- <div class="free-ongkir" v-if="item.shipping_fee_discount != null">
                            {{item.shipping_fee_discount | RupiahFormat}}
                        </div> -->
                        <div class="free-shipping" v-if="item.shipping_fee_discount != null"></div>
                        <div class="voucher-available"></div>
                        <div class="badge-rejeki-nomplok" v-if="item.in_rejeki_nomplok"></div>
                    </div>
                    <div class="left-stock">
                        <div class="progress-border">
                            <div class="progress-bar" style="" v-bind:style="{width : item.indikator.indicator + '%'}" ></div>
                        </div>
                        <small class="leftover">Sisa {{ item.stock }}</small>
                    </div>
                </div>
            </router-link>
            <div class="vertical-btn">
                <wish-component class="add-to-wish" :clases="item.in_wish_list ? 'active' : '' " :slug="item.slug"></wish-component>
                <add-to-cart-small :product="item" @updateCarts="$emit('updateCarts' , $event)"></add-to-cart-small>
            </div>
        </div>
    </div>
</template>
<script>
    import WishComponent from '../WishComponent.vue'
    import AddToCartSmall from '../AddToCartSmall.vue'
    import Rating from '../Rating.vue'
    export default {
        data(){
            return {
                productList : [],
                interval : null
            }
        },
        name: "SmallCardProduct",
        props :['item'],
        computed: {
            _sisa: () => 60,
            _total() {
                return 130;
            },
            _interval(){
                return (this._sisa / this._total) * 100;
            },
        },
        mounted(){
            this.interval = Math.round(this._interval)
        },
        components : {
            WishComponent,
            AddToCartSmall,
            Rating
        },
    }
</script>

<style scoped>

</style>
