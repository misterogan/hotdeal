<template>
    <div>
        <router-view></router-view>
    </div>
</template>

<script>
    import Vue from 'vue';
    import VueAnalytics from 'vue-analytics';
    Vue.use(VueAnalytics, {
        id: 'G-40J5HFEYRC'
    })
    export default {
        name: "App.vue"
    }
</script>

<style scoped>

</style>
