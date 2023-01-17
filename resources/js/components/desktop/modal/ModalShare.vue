<template>
    <div id="modal_share" class="modal">
            <div class="modal-dialog w-500 rounded-20">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal" v-if="Object.keys(products).length > 0">
                        <div class="share-dialog">
                            <div class="d-flex align-start mbottom-30">
                                <VueSlickCarousel 
                                    class="col-3 slider-small" 
                                    :arrows="true" 
                                    :dots="true" 
                                    :autoplay="false" 
                                    :infinite="true">
                                    <div class="product-slide" v-for="(image , index) in products.galleries" :key="index">
                                        <video v-if="image.type === 'video'" style="overflow:hidden;" height="100%" width="100%" controls :src="image.url" controlsList="nodownload" preload="metadata"></video>
                                        <img v-else :src="image.url" :alt="image.variant" :class="'var-gal var-' + image.variant" :data-index="index">
                                    </div>
                                </VueSlickCarousel>
                                <div class="col-9 mleft-30">
                                    <s class="fs-grey fs-18 fw-300" v-if="products.discount.nominal > 0">{{products.discount.nominal | RupiahFormat}}</s>
                                    <div class="d-flex align-center mbottom-20">
                                        <h2 class="mright-5 fp-pink">{{products.detail.price}}</h2>
                                        <div class="discount" v-if="products.discount.value">{{products.discount.value}}%</div>
                                    </div>
                                    <h5 class="fs-black mbottom-5">{{products.name}}</h5> 
                                    <div class="d-flex align-center justify-between">
                                        <div class="d-flex align-center">
                                            <rating class="mright-5" :rating="products.rating" :count_rating="products.total_review"></rating>
                                            <span class="fs-black fs-12 mright-5" v-if="products.total_review > 0">({{products.total_review}})</span>
                                        </div>
                                        <b class="fs-black fs-12" v-if="products.sold > 0">Terjual {{products.sold}}</b>
                                    </div>
                                </div>
                            </div>
                            <ul>
                                <li class="relative">
                                    <img class="copy pointer" @click="copy" src="/img/share_link.svg" width="40" alt="">
                                    <div class="tooltip hide">berhasil disalin</div>
                                </li>
                                <li>
                                    <a target="blank" class="wa-btn" :href="'whatsapp://send?text='+ url">
                                        <img src="/img/share_wa.svg" alt=""> 
                                    </a>   
                                </li>
                                <li>
                                    <a target="blank" class="tel-btn" :href="'https://t.me/share/url?url='+ url">
                                        <img src="/img/share_tel.svg" alt=""> 
                                    </a>   
                                </li>
                                <!-- <li>
                                    <a target="blank" class="ig-btn" href="https://www.instagram.com/hotdealindonesia/">
                                        <img src="/img/share_ig.svg" alt="">
                                    </a>
                                </li> -->
                                <li>
                                    <a target="blank" class="twitter-btn" :href="'https://twitter.com/share?url='+ url">
                                        <img src="/img/share_twitter.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a target="blank" class="facebooks-btn" :href="'http://www.facebook.com/sharer.php?u='+ url">
                                        <img src="/img/share_fb.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a target="blank" class="line-btn" :href="'https://social-plugins.line.me/lineit/share?url='+ url">
                                        <img src="/img/share_line.svg" alt="">
                                    </a>
                                </li>
                            </ul>
                            <!-- <h5 class="text-center">bagikan konten</h5>
                            <div class="link">
                                <h5>link</h5>
                                <h5 style="color: #B3B3B3; font-weight: 400; text-transform: lowercase;" class="link-content">{{url}}</h5>
                                <img @click="copy" class="copy pointer" style="width:13px; margin-left:5px;" src="/img/ic_copy.svg" alt="">
                                <img class="copied hide" src="/img/ic_checklist.svg" alt="">
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template> 

<script>
    import VueSlickCarousel from 'vue-slick-carousel'
    import 'vue-slick-carousel/dist/vue-slick-carousel.css'
    import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
    import Rating from '../../desktop/component/Rating.vue'

    export default {
        name: "ModalShare.vue",
        props : ['url', 'products'],
        data(){
            return{
                second : 2,
            }
        },
        methods : {
            copy() {
                navigator.clipboard.writeText(this.url);
                $('.tooltip').addClass("active")

                setTimeout(
                    function(){
                        $('.tooltip').removeClass("active")
                    },1000
                );  
            },
        },
        components: {
            VueSlickCarousel,
            Rating
        }
    }
</script>

<style>
</style> 