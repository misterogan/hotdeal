<template>
    <div id="modal_load" class="modal">
        <div class="loading-modal">
            <div class="spin">
                <img src="/img/logo_heart.svg" alt="">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ModalLoading.vue"
    }
</script>

<style>
.loading-modal{
    background: transparent!important;
    position: absolute;
    top: 50%;
    transform: translate(-50%,-50%);
    left: 50%;
}
</style> 