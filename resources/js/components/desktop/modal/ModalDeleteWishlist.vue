<template>
    <div id="confirmation_delete_modal" class="modal">
            <div class="modal-dialog purple">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="confirmation">
                            <div class="row">
                                <h5>Pilihanmu akan dihapus</h5>
                            </div>
                            <div class="row">
                                <div class="btn-option">
                                    <button @click="$emit('dodelete' ,status)">
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
    import CustomerAPi from '../../../apis/Customer'
    import CloseModal from '../component/CloseModal.vue'
    export default {
        name: "ModalDeleteWishlist.vue",
        data() {
            return {
            }
        },
        props :['product' , 'status_deleted' , 'status'],
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