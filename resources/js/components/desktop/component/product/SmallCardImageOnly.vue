<template>
    <div class="card">
        <router-link :to="'/product-detail/'+item.slug">
            <div class="card-img">
                <img :src="item.square" :alt="item.name">
            </div>
        </router-link>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                
            }
        },
        name: "SmallCardImageOnly",
        props :['item'],
        mounted(){
        }
    }
</script>
