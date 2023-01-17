<template>
     <div class="mbottom-10">
            <div class="ph-row justify-between">
                <div class="col-6 mright-10" v-for="(item, index) in count" :key="index">
                    <div class="ph-col-12 ph-h200 bg-placeholder"></div>
                    <div class="ph-row mtop-10">
                        <div class="ph-col-1 bg-placeholder mright-10"></div>
                        <div class="ph-col-1 bg-placeholder mright-10"></div>
                        <div class="ph-col-1 bg-placeholder mright-10"></div>
                        <div class="ph-col-1 bg-placeholder mright-10"></div>
                        <div class="ph-col-1 bg-placeholder mright-10"></div>
                    </div>
                    <div class="ph-row mtop-10">
                        <div class="ph-col-10 bg-placeholder mright-10"></div>
                    </div>
                    <div class="ph-row mtop-10">
                        <div class="ph-col-5 bg-placeholder mright-10"></div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
        name: "LargeSkeleton.vue",
        props:['count']
    }
</script>

<style scoped>

</style>