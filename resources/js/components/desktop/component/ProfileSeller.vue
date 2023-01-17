<template>
    <div class="co-seller d-flex align-center">
        <img class="mright-20" :src="seller.image" :alt="seller.name">
        <div>
            <b class="fs-black">{{seller.name}}</b>
            <h6 class="fs-black fw-400">{{seller.city.name}}, {{seller.province.name}}</h6>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ProfileSeller",
        data(){return{}},
        props :['seller']
    }
</script>