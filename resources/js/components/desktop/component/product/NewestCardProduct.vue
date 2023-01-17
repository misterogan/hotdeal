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
            <h3 class="title-section">Produk Menarik Lainnya</h3>
            <!-- <div class="see-all">Lihat Semua</div> -->
        </div>
        <div class="section-product" v-if="Object.keys(list).length > 0 && !is_loading" v-for="(item1 , index1) in list"  :key="index1">
            <small-card-product-without-stock-highlight v-if="Object.keys(item).length > 0" :item="item" v-for="(item , index) in item1"  :key="index" @updateCarts="$emit('updateCarts' , $event)"></small-card-product-without-stock-highlight>
        </div>
        <!-- <pagination :total="total_page" :current="current_page" @changeAction="changeActionPagination($event)"></pagination> -->
    </div>
</template>
    
    <script>
        import CustomerAPi from '../../../../apis/Customer'
        import SkeletonProductWithDetail from '../../../loading/SkeletonProductWithDetail.vue'
        import SmallCardProductWithoutStockHighlight from './SmallCardProductWithoutStockHighlight.vue'
        import CardSkeleton from '../../../skeleton/CardSkeleton.vue'
        import Pagination from '../../../pagination/v2/pagination.vue';

    
        export default {
            name: "HighLightProductTwoColumn",
            data(){
                return {
                    list : Object,
                    is_loading : true,
                    observer: null,
                    intersected: false,
                    highlight: '',
                    total_page: 1,
                    current_page: 1,
                }
            },
            mounted(){
                this.list_product(1);
            },
            methods: {
                list_product(page){
                    CustomerAPi.highlight_random_product({page: page}).then( response => {
                        this.list = response.data.data.products;
                        this.total_page = response.data.data.total_page
                        this.current_page = response.data.data.current_page
                        this.is_loading = false;
                        this.intersected = true;
                    })
                },
                changeActionPagination(event){
                    if(event < 1 || event > this.total_page){
                        return false;
                    }
                    this.list_product(event);
                    
                },
            },
            components : {
                SmallCardProductWithoutStockHighlight,
                SkeletonProductWithDetail,
                CardSkeleton,
                Pagination
            }
        }
    </script>
    
    <style scoped>
    
    </style>
    