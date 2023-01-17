<template>
<div>
    <div id="preview_image" class="modal">
        <div class="modal-dialog w-350">
            <div class="modal-body">
                <span class="close-modal" @click="close_modal"></span>
                <div class="content-modal">
                    <div class="preview-image">
                        <img id="preview_image" v-if="data.type != 'video'" :src="data.url_source" alt="">
                        <video v-else style="width: 100%;border-radius:15px" autoplay :src="data.url_source" controls controlsList="nodownload"></video>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>
</template>

<script>

import CloseModal from '../component/CloseModal.vue'

export default {
    name: "ModalPreviewImage.vue",
    props:['data'],

    components:{
        CloseModal
    },
    methods: {
        close_modal(){
            $('.modal').fadeOut();
            $("body").addClass('overflow-scroll');
        }
    },
}
</script>

<style>
</style>