<template>
    <div>
        <h3 class="title-section mbottom-15">Ulasan Produk ({{ reviews.length }})</h3>
        <div class="box-90p-white">
            <div class="row">
                <div class="col-12">
                    <div class="filter web">
                        <img class="toggle-filter" src="/img/assets_hotdeal_filter.svg" alt="">
                        <div class="overlay-transparent d-none"></div>
                        <ul class="small-box-filter">
                            <li class="filter-title-mobile">
                                <div class="fs-12 fp-black pl-12">Filter</div>
                                <img class="toggle-filter" src="/img/assets_hotdeal_filter.svg" alt="">
                            </li>
                            <li v-bind:class="paginate.filter === 'all' ? 'active' : ''" @click="getReview(paginate.filter = 'all', paginate.page = 1)">Semua Ulasan</li>
                            <li v-bind:class="paginate.filter === 'newest' ? 'active' : ''" @click="getReview(paginate.filter = 'newest', paginate.page = 1)">Terbaru</li>
                            <li v-bind:class="paginate.filter === 'high_rating' ? 'active' : ''" @click="getReview(paginate.filter = 'high_rating', paginate.page = 1)">Rating Tertinggi</li>
                            <li v-bind:class="paginate.filter === 'low_rating' ? 'active' : ''" @click="getReview(paginate.filter = 'low_rating', paginate.page = 1)">Rating Terendah</li>
                            <li v-bind:class="paginate.filter === 'with_video' ? 'active' : ''" @click="getReview(paginate.filter = 'with_video', paginate.page = 1)">Dengan Foto &amp; Video Ulasan</li>
                            <li class="rate" v-bind:class="paginate.filter === 'rating_5' ? 'active' : ''" @click="getReview(paginate.filter = 'rating_5', paginate.page = 1)">
                                <img src="/img/active_rating.svg" alt="">
                                <h6 class="fs-orange">5.0</h6>
                            </li>
                            <li class="rate" v-bind:class="paginate.filter === 'rating_4' ? 'active' : ''" @click="getReview(paginate.filter = 'rating_4', paginate.page = 1)">
                                <img src="/img/active_rating.svg" alt="">
                                <h6 class="fs-orange">4.0</h6>
                            </li>
                            <li class="rate" v-bind:class="paginate.filter === 'rating_3' ? 'active' : ''" @click="getReview(paginate.filter = 'rating_3', paginate.page = 1)">
                                <img src="/img/active_rating.svg" alt="">
                                <h6 class="fs-orange">3.0</h6>
                            </li>
                            <li class="rate" v-bind:class="paginate.filter === 'rating_2' ? 'active' : ''" @click="getReview(paginate.filter = 'rating_2', paginate.page = 1)">
                                <img src="/img/active_rating.svg" alt="">
                                <h6 class="fs-orange">2.0</h6>
                            </li>
                            <li class="rate" v-bind:class="paginate.filter === 'rating_1' ? 'active' : ''" @click="getReview(paginate.filter = 'rating_1', paginate.page = 1)">
                                <img src="/img/active_rating.svg" alt="">
                                <h6 class="fs-orange">1.0</h6>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="review" v-if="Object.keys(reviews).length > 0">
                <div class="row-100 content-review" v-for="(item , index) in reviews" :key="index">
                    <div class="col-2 d-none-mobile">
                        <div class="container-img">
                            <img :src="item.user.image != null ? item.user.image : '/img/pic_user.svg'" alt="">
                        </div>
                    </div>
                    <div class="col-10">
                        <div class="profile-mobile">
                            <img class="d-profile-mobile d-none" :src="item.user.image != null ? item.user.image : '/img/pic_user.svg'" alt="">
                            <div class="date-review-mobile">
                                <div class="profile-mobile">
                                    <div class="fs-grey fs-12 fw-400">{{format_date(item.created_at)}}</div>
                                    <rating-small :rating="item.rating" :count_rating="0"></rating-small>
                                </div>
                                <h5 class="mtop-5 fs-black fw-600">{{item.user.name}}</h5>
                            </div>
                        </div>
                        <div class="fs-black">{{item.review}}</div>
                        <div class="row-100 align-center">
                            <div class="media-review" v-for="(image , idx) in item.review_gallery" :key="idx">
                                <div class="video-review" v-if="image.type == 'video' && image.url_source != ''">
                                    <video :src="image.url_source" @click="modalCarousel(item, item.user.name, item.review, item.rating, item.created_at, item.user.image)" preload="metadata"></video>
                                    <img class="playvideo" src="/img/ic_play.svg" alt="">
                                </div>
                                <div class="img-review" v-if="image.type == 'image' && image.url_source != ''">
                                    <img :src="image.url_source" :alt="item.review" @click="modalCarousel(item, item.user.name, item.review, item.rating, item.created_at, item.user.image)">
                                </div>
                            </div>
                        </div>
                        <!-- <p class="fs-11 fs-black mtop-5 mbottom-5" v-if="item.vendor_review != null">Balasan</p> -->
                        <!-- <div class="d-flex align-start mbottom-10 relative" v-if="item.vendor_review != null">
                            <img class="img-seller" :src="vendor.image" alt="vendor-name">
                            <div :class="'d-flex flex-column review-reply reply-id-' + index">
                                <b class="fw-600 fs-black">{{vendor.name}}</b>
                                <span :class="'fs-black fw-300 review-expand expand-reply-id-' + index" class=" ">
                                    {{ item.vendor_review.reply }} 
                                </span>
                                <span id="seemore" @click="seeMore(index)" class="fp-purple mtop-5 pointer fs-12 fw-600"></span>
                            </div>
                        </div> -->
                    </div>
                </div>
                <button class="btn-link pointer d-flex m-auto mtop30 mbottom-15" @click="loadMore" v-if="btn_loadmore">Lihat ulasan lainnya</button>
            </div>
            <div class="blank-review" v-else>
                <blank-page :message="'Belum Ada Review Untuk Produk Ini'" :image="'/img/animation_empty_review.svg'"></blank-page>
            </div>
        </div>
        <!-- <modal-preview-image :data="source_data" ></modal-preview-image> -->
        <modal-carousel-image :data="source_data" :total_gallery="gallery_total" :name="user_review_name" :review="user_review" :rating="user_rating" :date="review_date" :pic="user_pic"></modal-carousel-image>
    </div>

</template>

<script>
import RatingSmall from './RatingSmall.vue'
import apiCustomer from "../../../apis/Customer" 
import BlankPage from '../BlankPage.vue'
import ModalPreviewImage from '../modal/ModalPreviewImage.vue'
import moment from 'moment'
import { minValue } from 'vuelidate/lib/validators'
import ModalCarouselImage from '../modal/ModalCarouselImage.vue'

    export default {
        name: "Review",
        components :{
            RatingSmall, BlankPage, ModalPreviewImage,
                ModalCarouselImage 
        },
        props :['review', 'product_id'],
        data(){
            return {
                reviews : this.review,
                img_url : {},
                track : {},
                selected_logs : {},
                invoice_response : {},
                paginate : { 
                    page : 1,
                    current_page : 1,
                    total : 1,
                    filter : 'all',
                    product_id: this.product_id
                },
                source_data:{},
                product : {},
                gallery_total: 0,
                vendor: {},
                btn_loadmore: true,
                user_review_name: '',
                user_rating: 0,
                user_review: '',
                review_date: '',
                user_pic: ''
            }
        },
        mounted(){
            this.getReview();
        },
        updated: 
        function(){
            $('.review-expand').addClass('active');
            let h = $('.review-expand').height();
            if(h >= 35){
                $('.review-expand').addClass('active');
                $('#seemore').addClass('active');
            } else{
                $('#seemore').remove();
                $('.review-expand').addClass('mbottom-30');
            }
        },
        methods : { 
            seeMore(index){
                $(".reply-id-"+index).toggleClass("active");
                $(".expand-reply-id-"+ index).toggleClass("active");
            },
            getReview() {
                apiCustomer.getReviews(this.paginate).then( response => {
                    if(response.data.code === 200) {
                        if(this.paginate.page == 1){
                            this.reviews = response.data.data.reviews.data
                        }else{
                            this.reviews.push.apply( this.reviews, response.data.data.reviews.data)
                        }
                        this.vendor = response.data.data.vendor
                        this.paginate.page = response.data.data.reviews.current_page
                        this.paginate.current_page = response.data.data.reviews.current_page
                        this.paginate.total = response.data.data.reviews.total
                        
                        if(Object.keys(response.data.data.reviews.data).length < 10){
                            this.btn_loadmore = false
                        }
                    }
                })
            },
            format_date(date){
                if (date){
                    return moment(String(date)).format('DD MMMM YYYY')
                }
            },
            modalCarousel(data, name, review, rating, date, pic){
                this.source_data = {}
                this.source_data = data
                this.gallery_total = this.source_data.review_gallery.length
                this.user_review_name = name
                this.user_review = review
                this.user_rating = rating
                this.review_date = date
                this.user_pic = pic
                $("#carousel_preview").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            loadMore(){
                this.paginate.page++;
                this.getReview();
            },
        },
    }
</script>
