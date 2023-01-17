<template>
    <div>
        <cart-list @updateCarts="$emit('updateCarts' , $event)"></cart-list>
        <!-- <high-light-product-two-column @updateCarts="$emit('updateCarts' , $event)"></high-light-product-two-column>
        <high-light-image-only></high-light-image-only>
        <high-light-video-product @updateCarts="$emit('updateCarts' , $event)"></high-light-video-product> -->
    </div>
</template>

<script>
    
    import Modal from '../../components/desktop/Modal'
    import CartList from "../desktop/component/CartList.vue"
    import CartSummary from '../desktop/component/CartSummary.vue'
    import HighLightProductTwoColumn from "./component/product/HighLightTwoColumn.vue"
    import HighlightProduct from './component/product/HighlightProduct.vue'
    import HighLightImageOnly from './component/product/HighLightImageOnly.vue'
    import HighLightVideoProduct from './component/product/HighlightVideoProduct.vue'
    export default {
        name: "Cart.vue",
        data(){
            return {
                selected_item : []
            }
        },
        methods: {
            show_modal_video(){
                $("#video_produk_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_confirm(){
                $("#confirmation_delete_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            trygetselecteditem(event){
               this.selected_item = event;
            }
        },
        components:{
            Modal,
            CartList,
            CartSummary,
            HighlightProduct,
            HighLightProductTwoColumn,
            HighLightImageOnly,
            HighLightVideoProduct
        },
                
    }
</script>

<style scoped>

</style>