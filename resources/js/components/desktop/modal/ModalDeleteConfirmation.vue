<template>
    <div id="delete_modal_confirmation" class="modal">
            <div class="modal-dialog purple">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="confirmation">
                            <div class="row">
                                <h5>Yakin akan menghapus ?</h5>
                            </div>
                            <div class="row">
                                <div class="btn-option">
                                    <button @click="$emit('dodelete' , {status : true , for : deleteFor , item})">
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
        name: "ModalDeleteConfirmation.vue",
        data() {
            return {
            }
        },
        props :['deleteFor' , 'item'],
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