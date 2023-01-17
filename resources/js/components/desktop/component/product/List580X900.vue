<template>
    <router-link :to="'/product-detail/'+item.slug">
        <div class="container-img">
            <img :src="item.image.link" :alt="item.name">
        </div>
    </router-link>
</template>
<script>
    export default {
        name: "List580X900",
        props :['item','additional_class']
    }
</script>

<style scoped>

</style>