<template>
   <div>
        <div id="refund_confirm_modal_bill" class="modal">
            <div class="modal-dialog w-400">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="refund">
                            <h5 class="text-center">Konfirmasi refund dengan mengirimkan detail informasi rekening tujuan pengembalian dana</h5>
                            <div class="form-input">
                                <h6>atas nama</h6>
                                <input v-model="form.account_name" type="text" placeholder="nama penerima">
                                <h6>bank penerima</h6>
                                <input v-model="form.bank_name" type="text" placeholder="bank tujuan">
                                <h6>nomor rekening bank penerima</h6>
                                <input v-model="form.account_number" type="text" placeholder="rekening tujuan">
                                <h6>upload KTP</h6>
                                <div class="media-upload mright-10">
                                    <label for="identity">
                                        <div class="upload-icon" style="width:30%; cursor: pointer">
                                            <img width="100%" class="icon preview_image" src="/img/ic_upload.svg">
                                        </div>
                                    </label>
                                    <input style="display:none" @change="imageIdentity($event)" name="identity" type="file" class="add-image" id="identity" accept=".jpg,.jpeg,.png">
                                </div>
                                <button class="btn-primary" @click="submitRefundAccountBank">ok</button>
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
        name: "ModalRefundBankAccount.vue",
        props: ['refund','invoice'],
        data() {
            return {
                form : {
                    account_name : '',
                    account_number : '',
                    bank_name : '',
                    identity_image : ''
                }
            }
        },
        methods: {
            close_modal(){
                $('.modal').fadeOut();
            },
            submitRefundAccountBank(){
                const fd = new FormData();
                fd.append('refund_id' , this.refund) 
                fd.append('invoice_number', this.invoice) 
                fd.append('account_name' , this.form.account_name) 
                fd.append('account_number', this.form.account_number) 
                fd.append('bank_name', this.form.bank_name) 
                fd.append('identity_image', this.form.identity_image) 
                apiCustomer.submitRefundAccountBank(fd).then( response => {
                    if(response.data.code == 200){
                        window.location.reload()
                       // this.show_refund_success();
                    }else{
                        //this.save_button = true;
                        Message.alert('Silahkan isi semua field dan foto', 'Informasi' , 1500);
                    }
                });
            },
            imageIdentity(event){
               if (event) {
                    $('.preview_image').attr('src' , URL.createObjectURL(event.target.files[0]));
                    this.form.identity_image = event.target.files[0]
                }
            },
        },
        components:{
                CloseModal,
                Message
        }
    }
</script>

<style>

</style>
