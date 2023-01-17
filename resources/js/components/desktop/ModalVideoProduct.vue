<template>
    <div id="video_produk_modal" class="modal">
        <div class="modal-dialog" v-if="Object.keys(product).length > 0">
            <div class="modal-body">
                <span class="close-modal" @click="close_modal"></span>
                <div class="content-modal">
                    <div class="modal-preview-video">
                        <div class="container-video">
                            <video v-if="product.video != null" style="overflow: hidden;max-height: 350px;" allowfullscreen="" controls="0" autoplay="0" controlsList="nodownload" frameborder="0" scrolling="no" :src="product.link != null ? product.link : product.video"></video>
                        </div>
                        <div class="mvideo-description">
                            <div class="col-4">
                                <div class="desc-product">
                                    <rating :rating="Math.ceil(product.rating)" :count_rating="product.review"></rating>
                                    <router-link :to="'/product-detail/'+product.slug">
                                        <h5 class="product-name">{{product.name}} 12345</h5>
                                    </router-link>
                                    <div class="price">
                                        <h5>{{product.label_face_price}}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="detail-seller">
                                    <img class="img-seller" :src="product.vendors.vendor.image" alt="">
                                    <div class="col">
                                        <h6 class="name-seller">{{product.vendors.vendor.name}}</h6>
                                        <h6 class="address-seller">{{product.vendors.vendor.city.name}},{{product.vendors.vendor.province.name}}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="buy">
                                    <img src="/img/ic_repurchase.svg" alt="">
                                    <h6 v-if="product.having_variant === true"><router-link :to="'/product-detail/' + product.slug">beli sekarang</router-link></h6>
                                    <h6 v-else>
                                        <a class="buynow" @click="buyNow('' ,1,'' , product.having_variant)">
                                            Beli Sekarang
                                        </a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="vertical-btn">
                            <wish-component class="add-to-wish" :clases="product.in_wish_list ? 'active' : '' " :slug="product.slug"></wish-component>
                            <add-to-cart-small :product="product" @updateCarts="$emit('updateCarts' , $event)"></add-to-cart-small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Customer from '../../apis/Customer'
import AddToCartSmall from './component/AddToCartSmall.vue';
import Rating from './component/Rating.vue';
import WishComponent from './component/WishComponent.vue';
import ModalMessage from './modal/ModalMessage.vue';
    export default {
        name: "ModalVideoProduct.vue",
        data() {
            return {
                have_variant: ''
            }
        },
        props :['product'],
        mounted(){
            this.have_variant = product.have_variant
            if(this.product === 1) {
                this.have_variant = true;
            }
        },
        methods: {
            close_modal(){
               this.$emit('stop_video' , {})
               $("body").removeClass('overflow-hidden');
            },
            buyNow(item_key_1 , quantity , item_key_2 , item){
                if(!item){
                    alert('pilih produk');
                }else{
                    Customer.buyNow({ pdid : item , qty : quantity , from_add_cart : true}).then( response => {
                        if(response.status === 401){
                            alert('Silahkan login terlebih dahulu');
                        }else{
                            window.location.href = '/cart'
                        }
                    });
                }
            },
        },
        mounted(){
        },
        components:{
                Rating,
                AddToCartSmall,
                WishComponent,
                ModalMessage
        }
    }
</script>

<style>

</style>
