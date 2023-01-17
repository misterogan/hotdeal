<template>
    <div>
        <div class="main-content">
            <div class="center-menu product" style="width:100%">
                <div class="main-menu">
                    <div class="row-100">
                        <div class="box product-list">
                            <h3>daftar pesanan</h3>
                            <div class="row-100 second-top">
                                <div class="col search">
                                    <div class="container-search">
                                        <form v-on:submit.prevent="listOrder(pagination.page = 1)">
                                            <input v-model="pagination.search" @keyup="listOrder(pagination.page = 1)" name="search" class="input-search" type="search" id="search" autocomplete="off" value="" placeholder="Masukan Nomor Invoice" />
                                            <img class="ic_search" src="/img/ic_search.svg" alt=""> 
                                        </form>
                                    </div>
                                </div>
                                <div class="col select date">
                                    <h5>tanggal pesanan</h5>
                                    <input v-model="pagination.date" type="date" name="daterange" value="11/01/2021 - 11/15/2021" @change="listOrder(pagination.page = 1)" />
                                </div>
                            </div>
                            <div class="row-100">
                                <div class="order-product">
                                    <div class="head-data">
                                        <ul>
                                            <li style="width:20%">review</li>
                                            <li style="width:25%">produk</li>
                                            <li style="width:15%">user</li>
                                            <li style="width:10%">status</li>
                                            <li style="width:15%">rating</li>
                                            <li style="width:15%"></li>
                                        </ul>
                                    </div>
                                    <div class="body-data">
                                        <div v-if="Object.keys(list).length > 0">
                                            <div class="row-data" v-for="(item , index) in list" :key="index">
                                                <div class="col" style="width:20%">
                                                    <ul>
                                                        <li class="fw-300">
                                                            {{item.review}}
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col" style="width:25%">
                                                    <div class="d-flex align-center">
                                                        <div class="container-img" v-if="item.main_photo != null">
                                                            <img :src="item.main_photo.link" alt="">
                                                        </div>
                                                        <ul>
                                                            <li class="product-name">{{item.product.name}}</li>
                                                            <li class="fw-300">SKU : {{item.product.sku}}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col" style="width:15%">
                                                    <ul>
                                                        <li>{{item.user.name}}</li>
                                                    </ul>
                                                </div>
                                                <div class="col" style="width:10%">
                                                    <div class="w-80">
                                                        <h6>{{item.status}}</h6>
                                                    </div>
                                                </div>
                                                <div class="col" style="width:10%">
                                                    <div class="w-80">
                                                        <h6>{{item.rating}}</h6>
                                                    </div>
                                                </div>
                                                <div class="col" style="width:15%">
                                                    <div>
                                                        <button @click="modalCarousel(item, item.user.name, item.review, item.rating, item.created_at, item.user.image, profile.image, item.id, item.vendor_review == null ? '' : item.vendor_review.reply)">detail review</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mtop-30" v-else>
                                            <blank-page :message="'Belum Ada Daftar Produk'" :image="'/img/animation_empty_wishlist.svg'"></blank-page>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-100 mtop-50">
                                <pagination :pagination="pagination" @updatePage="updatePage($event)"></pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal-reply-review :data="source_data" :total_gallery="gallery_total" :name="user_review_name" :review="user_review" :rating="user_rating" :date="review_date" :pic="user_pic" :vendor="vendor" :review_id="review_id" :reply="reply"></modal-reply-review>
    </div>
</template>

<script>
import apiVendor from '../../apis/Vendor'
import ModalDetailPesanan from '../desktop/modal/ModalDetailPesanan.vue'
import FilterMasterStatusOrder from '../pagination/FilterMasterStatusOrder.vue'
import Pagination from '../pagination/pagination.vue'
import ModalCancelOrder from './modal/ModalCancelOrder.vue'
import Message from '../../utils/Message'
import BlankPage from '../desktop/BlankPage.vue'
import ModalLoad from '../desktop/modal/ModalLoading.vue'
import ModalReplyReview from '../desktop/modal/ModalReplyReview.vue'
import User from '../../apis/User'


export default {
    data(){
        return {
            invoice_to_process : '',
            list : {},
            invoice_response : {},
            selected_logs : {},
            track : {},
            is_loading : true,
            pagination : {
                page : 1,
                current : 1,
                total : 1,
                status : '',
                date : '',
                search : '',
                filter : '',
                perpage : 10
            },
            source_data:{},
            gallery_total: 0,
            user_review_name: '',
            user_rating: 0,
            user_review: '',
            review_date: '',
            user_pic: '',
            profile: {},
            vendor: '',
            review_id: null,
            reply: ''
        }
    },
    mounted(){
        if(this.$route.query.filter != undefined){
            this.pagination.filter = this.$route.query.filter
        }
        this.listReview()
        this.get_profile()
    },
    methods : {
        listReview(){
            this.timer = setTimeout(() => {
                apiVendor.listReview(this.pagination).then( response =>{
                    this.list = response.data.data.review.data
                    this.pagination.total = response.data.data.total
                    this.pagination.current = response.data.data.current_page
                    this.is_loading = false
                });
            }, 500);
        },
        updatePage(e){
            if(e.type == 'page'){
                this.pagination.page = e.value
                if(this.pagination.total < e.value){
                    return ;
                }
            }
            if(e.type == 'filter'){
                this.pagination.filter = e.value
                this.pagination.page = 1
            }
            this.listReview()
        },
        modalCarousel(data, name, review, rating, date, pic, vendor, id, reply){
            this.source_data = {}
            this.source_data = data
            this.gallery_total = this.source_data.review_gallery.length
            this.user_review_name = name
            this.user_review = review
            this.user_rating = rating
            this.review_date = date
            this.user_pic = pic
            this.vendor = vendor
            this.review_id = id
            this.reply = reply
            $("#carousel_preview_vendor").fadeIn(function () {
                $("body").addClass('overflow-hidden');
            });
        },
        get_profile(){
            User.profile().then(response => {
                this.profile = response.data;
            })
        },
    },
    components :{
        Pagination,
        Message,
        BlankPage, ModalLoad, ModalReplyReview, User
    }
}
</script>
