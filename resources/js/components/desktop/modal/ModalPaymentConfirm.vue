<template>
    <div id="confirmation_payment_modal" class="modal">
            <div class="modal-dialog">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="confirmation">
                            <div class="row">
                                <h5>Apakah anda yakin mengkonfirmasi pembayaran untuk produk ini?</h5>
                            </div>
                            <div class="row">
                                <div class="btn-option">
                                    <button @click="doDelete">
                                        <img src="img/y.svg" alt="">
                                        <h6>Lanjutkan</h6>
                                    </button>
                                    <close-modal :attribute="'confirmation_delete_modal'"></close-modal>
                                </div>
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
        name: "ModalKonfirmasiPayment.vue",
        data() {
            return {
            }
        },
        methods: {
           
        },
        mounted(){
            
        },
        components:{
                CloseModal
        }
    }
</script>

<style>

</style>