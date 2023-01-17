<template>
    <div id="modal_success_change" class="modal">
        <div class="modal-dialog w-300">
            <div class="modal-body">
                <div class="content-modal">
                    <div class="notify-modal" style="padding: 25px; box-sizing: border-box;">
                        <h5 class="notify-text">Kata Sandi Anda Berhasil di perbarui</h5>
                        <img class="ilustrasi" src="/img/assets_success_change.svg">
                        <button class="btn-primary notify-button" href="javascript:void(0)" onclick="document.getElementsByClassName('close-modal')[0].click()">lanjut belanja</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ModalSuccessChange.vue",
    }
</script>

<style>
</style> 