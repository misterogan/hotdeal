<template>
    <div id="carousel_preview" class="modal">
        <div class="modal-dialog w-800">
            <div class="modal-body">
                <span class="close-modal" @click="close_modal"></span>
                <!-- <div v-if="is_loading">
                    <div class="ph-desktop">
                        <div class="ph-container-fluid p-20" >
                            <div class="ph-row">
                                <div class="ph-col-7 mright-10">
                                    <div class="bg-placeholder ph-h300 rounded-md"></div>
                                </div>
                                <div class="ph-col-5">
                                    <div class="ph-row mbottom-20">
                                        <div class="bg-placeholder ph-col-4 ph-h50 rounded-sm mright-10"></div>
                                        <div class="bg-placeholder ph-col-4 ph-h50 rounded-sm"></div>
                                    </div>
                                    <div class="ph-row">
                                        <div class="bg-placeholder ph-col-10 ph-h100 rounded-sm"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ph-mobile">
                        <div class="ph-container-fluid p-10" >
                            <div class="ph-row">
                                <div class="ph-col-7 mright-10">
                                    <div class="bg-placeholder ph-h300 rounded-md"></div>
                                </div>
                                <div class="ph-col-5">
                                    <div class="ph-row mbottom-20">
                                        <div class="bg-placeholder ph-col-4 ph-h50 rounded-sm mright-10"></div>
                                        <div class="bg-placeholder ph-col-4 ph-h50 rounded-sm"></div>
                                    </div>
                                    <div class="ph-row">
                                        <div class="bg-placeholder ph-col-10 ph-h100 rounded-sm"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="content-modal preview-carousel">
                    <div class="col-7 col-sm-12 review-image mright-20">
                        <div v-if="this.total_gallery > 1">
                            <img class="arrow slideprev" src="/img/ic_arrow_prev.svg" alt="" @click="arrowFunction(-1)">
                            <img class="arrow slidenext" src="/img/ic_arrow_next.svg" alt="" @click="arrowFunction(+1)">
                        </div>
                        <div class="image-item" v-for="(image , index) in data.review_gallery" :key="index">
                            <img class="mySlides" v-if="image.type == 'image' && index == 0" :src="image.url_source" alt="" :data-index="index">
                            <img class="mySlides" v-if="image.type == 'image' && index > 0" :src="image.url_source" alt="" :data-index="index" style="display: none;">
                            <video class="mySlides" v-if="image.type == 'video' && index == 0" style="overflow:hidden;" height="100%" width="100%" controls :src="image.url_source" controlsList="nodownload" preload="metadata" :data-index="index" autoplay></video>
                            <video class="mySlides" v-if="image.type == 'video' && index > 0" style="overflow:hidden; display: none;" height="100%" width="100%" controls :src="image.url_source" controlsList="nodownload" preload="metadata" :data-index="index" autoplay></video>
                        </div>
                    </div>
                    <div class="col-5 col-sm-12 d-flex flex-column">
                        <h5 class="mtop-5 fs-black fw-600 mbottom-15">Media Review</h5>
                        <div class="d-flex align-stretch flex-wrap w-100 mbottom-25">
                            <div class="media-review" v-for="(image , index) in data.review_gallery" :key="index">
                                <div class="img-review" v-if="image.type == 'image'">
                                    <img :src="image.url_source" alt="" >
                                </div>
                                <div class="video-review" v-else>
                                    <video :src="image.url_source" preload="metadata" :data-index="index"></video>
                                    <img class="playvideo" src="/img/ic_play.svg" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="content-review">
                            <div class="col-2 mright-10">
                                <div class="container-img">
                                    <img :src="pic != null ? pic : '/img/pic_user.svg'" alt="">
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="fs-grey fs-12 fw-300">{{format_date(date)}}</div>
                                <rating-small :rating="rating" :count_rating="0"></rating-small>
                                <h5 class="mtop-5 fs-black fw-600">{{name}}</h5>
                                <div class="fs-black">{{review}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</template>

<script>

    import CloseModal from '../component/CloseModal.vue'
    import VueSlickCarousel from 'vue-slick-carousel'
    import 'vue-slick-carousel/dist/vue-slick-carousel.css'
    import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
    import RatingSmall from '../component/RatingSmall.vue'
    import moment from 'moment'

export default {
    name: "ModalCarouselImage.vue",
    props:['data', 'total_gallery', 'name', 'review', 'rating', 'date', 'pic'],
    components:{
        VueSlickCarousel,RatingSmall,
        CloseModal
    },
    data() {
        return {
            medias : {},
            slideIndex : 1,
            is_loading : true,
        }
    },
    mounted(){
        this.showDivs(this.slideIndex);
    },
    methods: {
        showDivs(n) {
            var i;
            var x = document.getElementsByClassName("mySlides");
            if (n > x.length) {this.slideIndex = 1}
            if (n < 1) {this.slideIndex = x.length} ;
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            if(x[this.slideIndex-1] !== undefined){
                x[this.slideIndex-1].style.display = "block";
            }
        },
        close_modal(){
            $('.modal').fadeOut();
            $("body").addClass('overflow-scroll');
        },
        arrowFunction(n){
            this.showDivs(this.slideIndex += n);
        },

        format_date(date){
            if (date){
                return moment(String(date)).format('DD/MM/YYYY hh:mm')
            }
        },
    },
}
</script>