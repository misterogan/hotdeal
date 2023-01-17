<template>
    <div id="carousel_preview_vendor" class="modal">
        <div class="modal-dialog">
            <div class="modal-body">
                <span class="close-modal" @click="close_modal"></span>
                <div class="content-modal preview-carousel">
                    <div class="col-6 preview-image mright-20">
                        <div class="arrow" v-if="this.total_gallery > 1">
                            <img class="arrow-prev" style="cursor: pointer;" src="/img/ic_arrow_prev.svg" alt="" @click="arrowFunction(-1)">
                            <img class="arrow-next" style="cursor: pointer;" src="/img/ic_arrow_next.svg" alt="" @click="arrowFunction(+1)">
                        </div>
                        <div class="image-item" v-for="(image , index) in data.review_gallery" :key="index">
                            <img id="image-item" class="mySlides" v-if="image.type == 'image' && index == 0" :src="image.url_source" alt="" :data-index="index" style="background-color: #000000;">
                            <img id="image-item" class="mySlides" v-if="image.type == 'image' && index > 0" :src="image.url_source" alt="" :data-index="index" style="background-color: #000000; display: none;">
                            <video id="image-item" class="mySlides" v-if="image.type == 'video' && index == 0" style="overflow:hidden; background-color: #000000;" height="100%" width="100%" controls :src="image.url_source" controlsList="nodownload" preload="metadata" :data-index="index" autoplay></video>
                            <video id="image-item" class="mySlides" v-if="image.type == 'video' && index > 0" style="overflow:hidden; background-color: #000000; display: none;" height="100%" width="100%" controls :src="image.url_source" controlsList="nodownload" preload="metadata" :data-index="index" autoplay></video>
                        </div>
                    </div>
                    <div class="col-6 d-flex flex-column">
                        <h5 class="mtop-5 fs-black fw-600 mbottom-15">Reply Review</h5>
                        <div class="d-flex align-stretch flex-wrap w-100 justify-between mbottom-25">
                            <div class="container-media" v-for="(image , index) in data.review_gallery" :key="index">
                                <img :src="image.url_source" alt="" v-if="image.type == 'image'">
                                <video id="image-item" v-else height="100%" width="100%" controls :src="image.url_source" controlsList="nodownload" preload="metadata" :data-index="index" autoplay></video>
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
                                <h5 class="mtop-5 fs-black fw-600">{{user_review_name}}</h5>
                                <div class="fs-black">{{review}}</div>
                            </div>
                        </div>
                        <div class="content-review mtop-10">
                            <div class="col-2 mright-10">
                                <div class="container-img">
                                    <img :src="vendor != null ? vendor : '/img/pic_user.svg'" alt="">
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="fs-grey fs-12 fw-300">balasan</div>
                                <div class="fs-black">
                                    <textarea class="form-control" v-model="reply" name="reply" cols="25" rows="4" placeholder="kirim balasan"></textarea>
                                </div>
                                <div>
                                    <button class="btn btn-primary mtop-10" @click="submitForm(reply, review_id)">kirim reply</button>
                                </div>
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
    import Vendor from '../../../apis/Vendor'


export default {
    name: "ModalCarouselImage.vue",
    props:['data', 'total_gallery', 'user_review_name', 'review', 'rating', 'date', 'pic', 'vendor', 'vendor_id', 'review_id', 'reply'],
    components:{
        VueSlickCarousel,
        CloseModal,
        RatingSmall
    },
    data() {
        return {
            medias : {},
            slideIndex : 1,

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
            // document.getElementById("preview_review").removeAttribute('src');
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
        submitForm(reply, id){
            const fd = new FormData();
            fd.append('reply' , reply)
            fd.append('id' , id)
            Vendor.replyReview(fd).then( response => {
                if(response.data.code == 200){
                    window.location.reload();
                }else{
                    Message.alert(response.data.message, 'Informasi' , 1500);
                }
            });
        },
    },
}
</script>