<template>
    <div>
        <detail-product :key="$route.fullPath" @updateCarts="$emit('updateCarts' , $event)"></detail-product>
        <high-light-image-only></high-light-image-only>
        <high-light-video-product @updateCarts="$emit('updateCarts' , $event)"></high-light-video-product>
        <modal></modal>
    </div>
</template>

<script>
    import Modal from '../../components/desktop/Modal'
    import HighLightImageOnly from './component/product/HighLightImageOnly.vue'
    import HighLightVideoProduct from './component/product/HighlightVideoProduct.vue'
    import DetailProduct from './DetailProduct.vue'
    export default {
        data(){
            return {
               
            }
        },
        name: "ProductDetail.vue",
        mounted(){
            
        },
        methods: {
           
        },
        components:{
            Modal,
            HighLightVideoProduct,
            HighLightImageOnly,
            DetailProduct,
        },
    }
</script>