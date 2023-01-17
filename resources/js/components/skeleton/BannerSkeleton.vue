<template>
    <div>
        <div class="ph-desktop">
            <div class="ph-row justify-between">
                <div class="ph-container" v-for="(item, index) in count" :key="index">
                    <div class="ph-row ph-h300 bg-placeholder rounded-md mtop-10 mbottom-30"></div>
                </div>
            </div>
        </div>
        <div class="ph-mobile">
            <div class="ph-row justify-between">
                <div class="ph-container" v-for="(item, index) in count" :key="index">
                    <div class="ph-row ph-h100 bg-placeholder rounded-sm mtop-10 mbottom-15"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "BannerSkeleton.vue",
        props:['count']
    }
</script>

<style scoped>

</style>