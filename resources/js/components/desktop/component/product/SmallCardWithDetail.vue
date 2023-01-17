<template>
    <div class="banner-content small" v-if="Object.keys(item).length > 0"> 
        <router-link :to="'/product-detail/'+item.slug">
            <img :src="item.square" :alt="item.name">
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
                        <div class="price">
                            <h5>{{item.label_face_price}}</h5>
                        </div>
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
        name: "SmallCardWithDetail",
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