<template>
   <ul class="rating-user">
        <!-- <li class="detail-product-time">{{format_date(item.created_at)}}</li> -->
        <li v-for="(item, index) in 5" :key="index">
            <img v-if="index < rating" src="/img/active_rating.svg" alt="">
            <img v-else src="/img/inactive_rating.svg" alt="">
        </li>
        <li class="fs-orange">{{rating}}</li>
    </ul>
</template>

<script>
    export default {
        name: "RatingSmall",
        props :['rating','count_rating'],
        data(){
            return {
                product : {}
            }
        },
        mounted(){
        },
        methods : {
            format_date(date){
                if (date){
                    return moment(String(date)).format('hh:mm')
                }
            }
        }
    }
</script>
