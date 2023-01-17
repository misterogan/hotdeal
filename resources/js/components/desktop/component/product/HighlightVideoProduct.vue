<template>
    <div>
        <div id="high_light_video" class="mbottom-10" v-if="is_loading">
            <div class="ph-container">
                <div class="ph-desktop">
                    <div class="ph-row justify-between mbottom-30">
                        <div class="ph-col-6 mright-10 ph-h300 rounded-md bg-placeholder"></div>
                        <div class="ph-col-6 mright-10 ph-h300 rounded-md bg-placeholder"></div>
                        <div class="ph-col-6 mright-10 ph-h300 rounded-md bg-placeholder"></div>
                        <div class="ph-col-6 mright-10 ph-h300 rounded-md bg-placeholder"></div>
                        <div class="ph-col-6 mright-10 ph-h300 rounded-md bg-placeholder"></div>
                        <div class="ph-col-6 ph-h300 rounded-md bg-placeholder"></div>
                    </div>
                </div>
                <div class="ph-mobile">
                    <div class="ph-row justify-between mbottom-30">
                        <div class="ph-col-12 ph-h300 bg-placeholder">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-center mbottom-15">
            <h3 class="title-section">{{!highlight ? 'Video Produk Pilihan' : highlight.title}}</h3>
            <!-- <div class="see-all">Lihat Semua</div> -->
        </div>
        <ul class="row highlight-video" v-if="Object.keys(list).length > 0 && !is_loading">
            <li class="item" v-for="(item , index) in list" :key="index">
                <div class="card video">
                    <div class="container-video">
                        <img class="preview-video" :src="item.portrait != null ? item.portrait : item.galleries" alt="" controlsList="nodownload" preload="metadata">                        
                    </div>
                    <div class="product-details">
                        <router-link class="pointer" :to="'/product-detail/'+item.slug">
                            <h5 class="product-name mbottom-5">{{item.name}}</h5>
                        </router-link>
                        <s class="strike" v-if="item.discount_value > 0">{{item.price_before_discount}}</s>
                        <div class="price">
                            <h5 class="mright-5">{{item.label_face_price}}</h5>
                            <div class="discount" v-if="item.discount_value > 0">{{item.discount_value}}%</div>
                        </div>
                        <div class="tag">
                            <div class="free-shipping">
                                <img src="/img/assets_free_ongkir.svg" alt="">
                            </div>
                        </div>
                    </div>
                    <button class="btn-play" href="javascript:void(0)" @click="show_modal_video(item)"></button>
                </div>
            </li>
        </ul>
        <modal-video-product :product='show_item' @stop_video="stop_video()" @updateCarts="update_cart($event)"> </modal-video-product>
        <!-- <div v-if="!is_loading">
            <div class="row">
                <h3 class="title-section mbottom-15">{{!highlight ? 'Rekomendasi Untukmu' : highlight.title}}</h3>
            </div>
            <div class="row product-large">
                <div class="card" v-for="(item , index) in list" :key="index">
                    <div class="item-large">
                        <div class="card-product-large">
                            <div class="video-preview">
                                <div class="container-img">
                                    <img :src="item.square" :alt="item.name">
                                </div>
                                <button class="btn-play" href="javascript:void(0)" @click="show_modal_video(item)">
                                    <img src="/img/ic_large_play.svg" alt="">
                                </button>
                            </div>
                            <router-link :to="'/product-detail/'+item.slug">
                                <div class="detail-product">
                                    <div class="row">
                                        <div class="col-2">
                                            <rating :rating="Math.ceil(item.rating)" :count_rating="item.review"></rating>
                                            <div class="tooltip">
                                                <h5 class="product-name">{{item.name}}</h5>
                                                <span class="tooltiptext">{{item.name}}</span>
                                            </div>
                                            <div class="price">
                                                <h5>{{item.label_face_price}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <router-link :to="'/product?search='+item.vendors.vendor.name">
                                                <div class="col-seller-large">
                                                    <div class="col text-right">
                                                        <h6 class="name-seller">{{item.vendors.vendor.name}}</h6>
                                                        <h6 class="address-seller">{{item.vendors.vendor.city.name}}, {{item.vendors.vendor.province.name}}</h6>
                                                    </div>
                                                    <img style="margin-right:0;" class="img-seller mleft-10" :src="item.vendors.vendor.image" alt="">
                                                </div>
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            <modal-video-product :product='show_item' @stop_video="stop_video()" @updateCarts="update_cart($event)"> </modal-video-product>
        </div> -->
    </div>
</template>

<script>
   import CustomerAPi from '../../../../apis/Customer'
   import Rating from '../Rating.vue'
   import SmallCardImageOnly from './SmallCardImageOnly.vue'
   import ModalVideoProduct from '../../ModalVideoProduct.vue'
    export default {
        name: "HighLightVideoProduct",
        data(){
            return {
                list : {},
                show_item : {},
                is_loading: true,
                observer: null,
                intersected: false,
                highlight: ''
            }
        },
        mounted(){
            this.list_product();
            this.list_highlight();
        },
        methods: {
            list_product(){
                const element = document.querySelector("#high_light_video");
                this.observer = new IntersectionObserver(entries => {
                const image = entries[0];
                if (image.isIntersecting) {
                    CustomerAPi.highlightVideo().then( response => {
                      this.list = response.data.data;
                      this.is_loading=false;
                      this.intersected = true;
                      this.observer.disconnect();
                    })
                  }
                });
                this.observer.observe(element);
            },
            list_highlight(){
                CustomerAPi.get_highlight_title().then( response => {
                    this.highlight = response.data.data.section_1
                    })
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
            update_cart(event){
                this.$emit('updateCarts' , event)
            }
        },
        components : {
            SmallCardImageOnly,
            Rating,
            ModalVideoProduct
        }

    }
</script>

<style scoped>

</style>
