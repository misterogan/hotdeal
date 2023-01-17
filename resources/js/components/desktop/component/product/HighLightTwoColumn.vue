<template>
<div>
    <div id="HighLightProductTwoColumn" class="mbottom-30" v-if="is_loading" >
        <div class="ph-desktop ph-container">
            <card-skeleton :count="6"></card-skeleton>
        </div>
        <div class="ph-mobile ph-container">
            <card-skeleton :count="2"></card-skeleton>
        </div>
    </div>
     <div class="row align-center mbottom-15">
        <h3 class="title-section">{{!highlight ? 'Produk Terlaris' : highlight.title}}</h3>
        <!-- <div class="see-all">Lihat Semua</div> -->
    </div>
    <div class="section-product" v-if="Object.keys(list).length > 0 && !is_loading">
        <small-card-product-without-stock-highlight :item="item" v-for="(item , index) in list"  :key="index" @updateCarts="$emit('updateCarts' , $event)"></small-card-product-without-stock-highlight>
    </div>
</div>
</template>

<script>
    import CustomerAPi from '../../../../apis/Customer'
    import SkeletonProductWithDetail from '../../../loading/SkeletonProductWithDetail.vue'
    import SmallCardProductWithoutStockHighlight from './SmallCardProductWithoutStockHighlight.vue'
    import CardSkeleton from '../../../skeleton/CardSkeleton.vue'

    export default {
        name: "HighLightProductTwoColumn",
        data(){
            return {
                list : Object,
                is_loading : true,
                observer: null,
                intersected: false,
                highlight: ''
            }
        },
        mounted(){
            this.list_product();
            this.list_highlight();
        },
        methods: {
            list_product(){
                const element = document.querySelector("#HighLightProductTwoColumn");
                this.observer = new IntersectionObserver(entries => {
                    const image = entries[0];
                    if (image.isIntersecting) {
                        CustomerAPi.highlightMiddle().then( response => {
                            this.list = response.data.data;
                            this.is_loading = false;
                            this.intersected = true;
                            this.observer.disconnect();
                        })
                    }
                });
                this.observer.observe(element);
                
            },
            list_highlight(){
                CustomerAPi.get_highlight_title().then( response => {
                    this.highlight = response.data.data.section_2;
                })
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
