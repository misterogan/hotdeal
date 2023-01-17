<template>
    <div id="modal_load_ovo" class="modal">
            <div class="modal-dialog w-350">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="loading-modal">
                            <h5 class="status">pembayaran</h5>
                            <h5>Referensi ID : 001</h5>
                            <div class="spin">
                                <img src="/img/logo_heart.svg" alt="">
                            </div>
                            <div class="text-center">
                                <h5 class="mbottom-10">pembayaran masih diproses</h5>
                                <h5 style="color: #B3B3B3; font-weight: 400; margin-bottom: 45px;">Silahkan periksa aplikasi ovo Anda di bawah “pemberitahuan” dan konfirmasi pembayaran dalam waktu 55 detik   </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
        name: "ModalLoadingOvo.vue"
        
    }
</script>

<style>
</style> 