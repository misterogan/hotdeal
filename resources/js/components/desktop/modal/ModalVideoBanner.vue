<template>
    <div id="modal_banner_video" class="modal">
        <div class="modal-dialog">
            <div class="modal-body">
                <span class="close-modal" @click="close_modal"></span>
                <div class="video-preview" v-if="Object.keys(dataurl).length > 0">
                    <div class="container-img">
                        <video width="100%"  :src="dataurl.video"  height="100%" :poster="dataurl.cover" playsinline="" autoplay="" muted="" loop="" id="#asdasd" controls controlsList="nodownload">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "ModalVideoBanner.vue",
        props : ['dataurl'],
        methods : {
            close_modal(){
                this.$emit('close_modal_video' , {cover : '' , video : ''})
            }
        }

    }
</script>

<style>

</style>
