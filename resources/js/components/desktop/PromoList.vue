<template>
    <div>
        <div class="promo-list">
            <div class="mbottom-10" v-if="is_loading">
                <div class="ph-container">
                    <div style="border-radius: 20px" class="ph-row ph-h200 bg-placeholder mtop-10 mbottom-20"></div>
                </div>
            </div>
            <div class="row" v-if="!is_loading">
                <div class="col-12" >
                    <div class="banner-flashsale">
                        <div class="container-img">
                            <img alt="" v-if="Object.keys(banner).length > 0 " :src="banner">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row col2-flex promo">
                <div class="col-20p">
                    <div class="mbottom-10" v-if="is_loading">
                        <div class="ph-row">
                            <div class="ph-col-12 ph-h100 bg-placeholder mright-10"></div>
                        </div>
                    </div>
                    <div v-if="!is_loading">
                        <button class="trigger" id="floating_btn">
                            <img src="/img/icon_filter_white.svg" alt="">
                            <span>filter</span>
                        </button>
                        <div class="slider-filter" v-if="Object.keys(category).length > 0 ">
                            <div class="close" @click="close_filter">
                                <img class="btn-bar" src="img/ic_bar_white.svg" alt="">
                                <img class="btn-close" src="img/ic_close_filter.svg" alt="">
                            </div>
                            <div class="leftside-menu f-child">
                            <h4>Promo</h4>
                                <ul class="leftside-submenu max-height" v-for="(item,index) in category" :key="index">
                                    <li class="leftside-content" v-bind:class="active_class == index ? 'active': ''" @click="active_class = index ; doFilter(index, item.category)">
                                        <h5>{{item.category}}</h5>
                                        <h5>{{item.total_voucher}}</h5>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-80p">
                    <large-skeleton v-if="is_loading" :count="2"></large-skeleton>
                    <div class="row-100" v-if="!is_loading">
                        <div class="promo" v-if="Object.keys(promolist).length > 0">
                            <div class="card-promo" v-for="(item, index) in promolist" :key="index" v-bind:class="active_category == item.category.category ? 'show' : 'hide'">
                                <div class="container-img">
                                    <img :src="item.image" :alt="item.voucher_name">
                                </div>
                                <div class="desc-promo">
                                    <h5>{{item.voucher_name}}</h5>
                                    <h6>{{item.voucher_description}}</h6>
                                    <div class="see-all" href="javascript:void(0)" @click="show_modal(item)">Selengkapnya</div>
                                </div>
                            </div>
                        </div>
                        <div class="m-auto" v-else>
                            <blank-page :message="'Saat Ini Belum Ada Promo Yang Tersedia'" :image="'img/animation_empty_promo.svg'"></blank-page>
                        </div>
                    </div>
                    <div class="row-100">
                        <ul class="pagination">
                            <li v-for="i in pagination.total" :key="i" :class="i== pagination.current_page ? 'active' :''">{{i}}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div>
        </div>
        <high-light-image-only></high-light-image-only>
        <high-light-video-product @updateCarts="$emit('updateCarts' , $event)"></high-light-video-product>
        </div>
        <modal-detail-promo :data="voucher_selected"></modal-detail-promo>
    </div>
</template>

<script>
    import apiCustomer from '../../apis/Customer'
    import HighLightImageOnly from './component/product/HighLightImageOnly.vue'
    import HighLightVideoProduct from './component/product/HighlightVideoProduct.vue'
    import LargeSkeleton from '../skeleton/LargeSkeleton.vue'
    import ModalDetailPromo from './modal/ModalDetailPromo.vue'
    import BlankPage from '../desktop/BlankPage.vue'

    export default {
        name: "PromoList.vue",
        data(){
            return {
                promolist : {},
                category : {},
                banner : {},
                is_loading : true,
                voucher_selected:{},
                pagination : {
                    total : 0,
                    per_page : 0,
                    current_page : 1,
                    search : '',
                    first_page : 1,
                },
                active_class : '0',
                active_category : ''
            }
        },
        mounted(){
            this.listPromo()
        },
        methods: {
            doFilter(index, value){
                this.active_class = index
                this.active_category = value
                $("html, body").animate({ scrollTop: 0 },500);
                $(".trigger").click();
                $("body").removeClass('overflow-hidden');
            },
            listPromo(){
                apiCustomer.promoList().then( response => {
                    this.is_loading = false;
                    this.promolist = response.data.data.vouchers.data
                    this.category = response.data.data.category
                    this.pagination.total = response.data.data.total
                    this.pagination.current_page = response.data.data.current_page
                    this.pagination.per_page = response.data.data.per_page
                    this.banner = response.data.data.banner
                    this.active_category = this.category[0].category
                });
            },
            show_modal(item){
                this.voucher_selected = {}
                this.voucher_selected = item
                $("#promo_detail_modal").fadeIn(function () {
                    $("#promo_detail_modal").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            close_modal(){
                $('#modal_crop_image').fadeOut(function () {
                    $("body").removeClass('overflow-hidden');
                });
            },
            close_filter(){
                $('.slider-filter').removeClass('show')
            }

        },
        components:{
            ModalDetailPromo,
            HighLightVideoProduct,
            HighLightImageOnly,
            LargeSkeleton,
            BlankPage

        },
    }
</script>

<style scoped>

</style>
