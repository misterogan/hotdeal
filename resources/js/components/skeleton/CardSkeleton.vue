<template>
        <div>
            <div class="ph-desktop">
                <div class="ph-row justify-between">
                    <div class="ph-col-12" v-for="(item, index) in count" :key="index">
                        <div class="ph-row">
                            <div class="ph-col-12 bg-placeholder ph-h200 mright-10 rounded"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-10 bg-placeholder mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-5 bg-placeholder mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-12 bg-placeholder mright-10"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ph-mobile">
                <div class="ph-row justify-between">
                    <div class="ph-col-12" v-for="(item, index) in count" :key="index">
                        <div class="ph-row">
                            <div class="ph-col-12 bg-placeholder ph-h150 mright-10 rounded-sm"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-10 bg-placeholder mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-5 bg-placeholder mright-10"></div>
                        </div>
                        <div class="ph-row mtop-10">
                            <div class="ph-col-12 bg-placeholder mright-10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
        name: "CardSkeleton.vue",
        props:['count']
    }
</script>

<style>

</style>
