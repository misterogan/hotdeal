<template>
    <div>
        <div class="mbottom-30" v-if="is_loading">
            <div class="ph-container">
                <div class="ph-desktop">
                    <div class="ph-row justify-between">
                        <div class="ph-col-12 ph-h400 mright-20 bg-placeholder rounded"></div>
                        <div class="ph-col-12 ph-h400 mright-20 bg-placeholder rounded"></div>
                        <div class="ph-col-12 ph-h400 bg-placeholder rounded"></div>
                    </div>
                </div>
                <div class="ph-mobile">
                    <div class="ph-row justify-between">
                        <div class="ph-col-12 ph-h100 mright-10 bg-placeholder rounded-sm"></div>
                        <div class="ph-col-12 ph-h100 mright-10 bg-placeholder rounded-sm"></div>
                        <div class="ph-col-12 ph-h100 bg-placeholder rounded-sm"></div>
                    </div>
                </div>
            </div>
        </div>
         <div class="row flash-product top-three-product" v-if="!is_loading && Object.keys(list).length > 0">
            <div class="item highlight-1" v-for="(item, index) in list" :key="index" v-if="index < 3">
                <div class="banner">
                    <router-link v-if="item.deep_link == null" :to="'/product-detail/'+item.slug" :target="item.new_tab == true ? '_blank' : ''">
                        <img class="d-block" :src="item.square" alt="">
                    </router-link>
                    <a :href="item.deep_link" :target="item.new_tab == true ? '_blank' : ''" v-else>
                        <img class="d-block" :src="item.square" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import List580X900 from '../product/List580X900.vue'
    import Customer from '../../../../apis/Customer'
    export default {
        data(){
            return {
                list : {},
                is_loading : true
            }
        },
        name: "HighlightProduct",
        mounted(){
            this.get_list();
        },
        methods : {
            get_list(){
                Customer.highlight().then(response => {
                    this.list = response.data.data;
                    this.is_loading = false;
                })
            },
            additional_class(i){
                if(i == 0){
                    return 'col-product'
                }else if(i == 1){
                    return 'col-product'
                }else if(i == 2){
                    return 'absolute'
                }else if(i == 3){
                    return 'last'
                }else if(i == 4){
                    return 'absolute2'
                }else{
                    return 'col-product'
                }
            }
        },
        components :{
            // List580X900
        }
    }
</script>

<style scoped>

</style>
