<template>
    <div>
        <div class="wishlist-page">
            <div class="row col2-flex">
                <wish-list-menu @checkedAll="checkall($event)" @action="action_menu($event)" :items="checked_item"></wish-list-menu>
                <div class="col-80p">
                     <div class="mbottom-10" v-if="is_loading">
                        <div class="ph-row justify-between mbottom-10">
                            <div class="ph-col-2 ph-h100 bg-placeholder mright-10"></div>
                            <div class="ph-col-9 ph-h100 bg-placeholder mright-10"></div>
                            <div class="ph-col-1 ph-h100 bg-placeholder"></div>
                        </div>
                        <div class="ph-row justify-between">
                            <div class="ph-col-2 ph-h100 bg-placeholder mright-10"></div>
                            <div class="ph-col-9 ph-h100 bg-placeholder mright-10"></div>
                            <div class="ph-col-1 ph-h100 bg-placeholder"></div>
                        </div>
                    </div>
                    <div v-if="!is_loading">
                        <ListProduct :items="wishlist" :checked_item="checked_item" @selected="checked_items($event)"></ListProduct>
                        <!-- <ListProduct :items="wishlist" :checked_item="checked_item" @video_data="show_modal_videos($event)" @selected="checked_items($event)"></ListProduct> -->
                    </div>
                </div>
            </div>
            <high-light-two-column></high-light-two-column>
            <high-light-image-only></high-light-image-only>
            <high-light-video-product @updateCarts="$emit('updateCarts' , $event)"></high-light-video-product>
        </div>
        <modal></modal>
        <modal-video-product :product='show_item' @stop_video="stop_video()"> </modal-video-product>
        <!-- <modal-delete-wishlist :product="checked_item" :status_deleted="deleteAll" @after_delete="updateTheCart"></modal-delete-wishlist> -->
    </div>
</template>

<script>
    import ModalVideoProduct from './ModalVideoProduct.vue'
    import apiCustomer from '../../apis/Customer'
    import Modal from '../../components/desktop/Modal'
    import WishListMenu from '../desktop/component/profile/WishlistMenu.vue'
    import ListProduct from './component/product/LargeCardProduct.vue'
    import HighLightTwoColumn from './component/product/HighLightTwoColumn.vue'
    import HighLightImageOnly from './component/product/HighLightImageOnly.vue'
    import HighLightVideoProduct from './component/product/HighlightVideoProduct.vue'
import { event } from 'vue-analytics'
    export default {
        name: "Wishlist.vue",
        data (){
            return {
                wishlist : {},
                show_item : {},
                checked_item : [],
                deleteAll : false,
                updateTheCart : false,
                is_loading : true,
                incheck_All : false
            }
        },
        mounted(){
            this.getWishlist();
        },
        methods: {
            getWishlist(){
                apiCustomer.wishlist().then( response => {
                   this.wishlist = response.data.data;
                   this.is_loading = false
                });
            },
            show_modal_video(){
                $("#video_produk_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_videos : function($event){
                this.show_item = $event;
                $("#video_produk_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            stop_video(){
               this.show_item = {};
               $('#video_produk_modal').fadeOut();
            },
            checked_items(event){
                this.checked_item = event
            },
            action_menu(event){
                apiCustomer.removeWishlist({item : this.checked_item , status:event}).then( response => {
                });
                if(event == 'all'){
                    this.wishlist = {};
                }else{
                    for (const i of this.checked_item) {
                      this.wishlist.splice(this.wishlist.findIndex(item => item.id === i), 1)
                    }
                }
                this.checked_item = []
                $("#confirmation_delete_modal").fadeOut(function () {
                    //$("body").addClass('overflow-hidden');
                });
            },
            checkall(){
                if(this.incheck_All === true){
                    this.checked_item = []
                    this.incheck_All = false
                }else{
                    this.incheck_All = true
                    for (const i of this.wishlist) {
                        this.checked_item.push(i.id)
                    }
                }
                 
            }
        },
        components:{
            Modal,
            WishListMenu,
            ListProduct,
            ModalVideoProduct,
            HighLightImageOnly,
            HighLightTwoColumn,
            HighLightVideoProduct
        },
    }
</script>

<style scoped>

</style>
