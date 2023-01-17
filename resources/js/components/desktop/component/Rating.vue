<template>
   <div class="rating" v-if="count_rating > 0">
        <img src="/img/active_rating.svg" alt="" width="15">
        <div class="fp-orange mleft-7">{{rating.toString().substr(0,3)}}</div>
        <div class="fs-black mleft-8" v-if="sold">Terjual {{sold.text}}</div>
    </div>
</template>

<script>
    // rating = rating value
    // count_rating = count of customer that give ratings
    // import CustomerAPi from '../../../apis/Customer'
    export default {
        name: "Rating",
        props :['rating','count_rating','sold'],
        mounted(){
        }
    }
</script>
