<template>
   <div class="banner-content large" v-if="Object.keys(item).length > 0">
        <router-link :to="'/product-detail/'+item.slug">
             <img :src="item.landscape" :alt="item.name">
            <div class="detail-product">
                <div class="row">
                     <rating :rating="item.total_review" :count_rating="item.rating"></rating>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="tooltip">
                            <h5 class="product-name">{{item.name}}</h5>
                            <span class="tooltiptext">{{item.name}}</span>
                        </div>
                        <div class="price">{{item.label_face_price}}</div>
                    </div>
                </div>
            </div>
        </router-link>
    </div>
</template>
<script>
    import Rating from '../Rating.vue'
    export default {
        data(){
            return {
            }
        },
        name: "LargeCardWithDetail",
        props :['item'],
        mounted(){
           
        },
        components : {
           Rating
        }
    }
</script>

<style scoped>

</style>