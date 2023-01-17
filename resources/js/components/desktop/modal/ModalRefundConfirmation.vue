<template>
   <div>
        <div id="refund_confirm_modal_detail" class="modal">
            <div class="modal-dialog w-400">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="refund">
                           <h5 class="text-center">Konfirmasi refund dengan mengirimkan detail informasi pengajuan refund</h5>
                            <div class="form-input">
                                <h6>nama</h6>
                                <input v-model="form.consignor" type="text" placeholder="Nama pengirim">
                                <h6>nomor resi</h6>
                                <input v-model="form.receipt_number" type="text" placeholder="Nomor Resi">
                                <h6>logistik yang digunakan</h6>
                                <input v-model="form.shipping_name" type="text" placeholder="Contoh : JNE - Reguler">
                                <button class="btn-primary" @click="submitConfirmation">kirim</button>
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
    import apiCustomer from '../../../apis/Customer'
    import Message from '../../../utils/Message'

    export default {
        name: "ModalRefundConfirmation.vue",
        props: ['refund','invoice','refund_form'],
        data() {
            return {  
                confirm_btn : true,
                form : {
                    invoice_number : '',
                    refund_id : '',
                    consignor : '',
                    receipt_number : '',
                    shipping_name : '',
                }
            }
        },
        methods: {
            close_modal(){
                $('.modal').fadeOut();
            },
            submitConfirmation(){
                if(this.confirm_btn == true){
                    this.confirm_btn == false
                    this.form.refund_id = this.refund
                    this.form.invoice_number = this.invoice
                    apiCustomer.submitRefundConfirmation(this.form).then( response => {
                        if(response.data.code == 200){
                            window.location.reload()
                        // this.show_refund_success();
                        }else{
                            //this.save_button = true;
                            this.confirm_btn = true;
                            Message.alert('Silahkan isi semua field.' , 'Informasi' , 1500)
                        }
                    });
                }
                
            }
        },
        components:{
                CloseModal
        }
    }
</script>

<style>

</style>
