<template>
    <div class="col box-recommended">
        <div id="ob_product_new" class="mbottom-30" v-if="is_loading">
            <div class="ph-desktop ph-container">
                <card-skeleton :count="6"></card-skeleton>
            </div>
            <div class="ph-mobile ph-container">
                <card-skeleton :count="2"></card-skeleton>
            </div>
        </div>
        <div v-if="!is_loading">
            <div class="row align-center mbottom-15">
                <h3 class="title-section">{{!highlight ? 'Spesial Untukmu' : highlight.title}}</h3>
            </div>
            <div class="section-product" v-if="Object.keys(list).length > 0">
                <small-card-product-without-stock-highlight v-for="(item , index) in list"  :key="index"  :item="item" @updateCarts="$emit('updateCarts' , $event)"></small-card-product-without-stock-highlight>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomerAPi from '../../../../apis/Customer'
    import apiCustomer from "../../../../apis/Customer";
    import SmallCardProductWithoutStockHighlight from './SmallCardProductWithoutStockHighlight.vue';
    import SkeletonProductWithDetail from '../../../loading/SkeletonProductWithDetail.vue'
    import CardSkeleton from '../../../skeleton/CardSkeleton.vue'

    export default {
        name: "HighLightImageOnly",
        data(){
            return {
                list : {},
                is_loading : true,
                observer: null,
                intersected: false,
                highlight: ''
            }
        },
        mounted(){
            this.list_product();
            this.highlight_title();
        },
        methods: {
            list_product(){
                CustomerAPi.highlightFooter().then( response => {
                    this.list = response.data.data;
                    this.is_loading = false;
                    this.intersected = true;
                })
            },
            highlight_title(){
                apiCustomer.get_highlight_title().then( response => {
                this.highlight = response.data.data.section_3
            });
        }
        },
        components : {
                SmallCardProductWithoutStockHighlight,
                SkeletonProductWithDetail,
                CardSkeleton
        }
    }
</script>

<style scoped>

</style>
