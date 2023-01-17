<template>
        <div>
            <div class="ph-desktop">
                <div class="ph-row justify-between">
                    <div v-for="(item, index) in count" :key="index">
                        <div class="bg-placeholder profile-ph-md"></div>
                    </div>
                </div>
            </div>
            <div class="ph-mobile">
                <div class="ph-row justify-between">
                    <div v-for="(item, index) in count" :key="index">
                        <div class="bg-placeholder profile-ph-sm"></div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
        name: "IconSkeleton.vue",
        props:['count']
    }
</script>

<style scoped>

</style>