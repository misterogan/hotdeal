<template>
   <div>
        <div id="refund_cancel_order" class="modal">
            <div class="modal-dialog w-400">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="refund">
                            <h5 class="text-center">Metode Pengembalian Dana</h5>
                            <div class="option-refund">
                                <div class="input-radio">
                                    <div class="radio">
                                        <input type="radio" value="hotpoint" v-model="form.refund_type"  id="point"/>
                                        <label for="point"></label>
                                    </div>
                                    <div class="label">
                                        <img src="/img/ic_hot_point.svg" alt="">
                                        refund dengan hot point
                                    </div>
                                </div>
                                <div class="input-radio">
                                    <div class="radio">
                                        <input type="radio" value="cash" v-model="form.refund_type" id="cash" />
                                        <label for="cash"></label>
                                    </div>
                                    <div class="label mleft-10">
                                        refund dengan cash
                                    </div>
                                </div>
                            </div>
                            <div class="form-input">
                                <div  v-if="form.refund_type == 'cash'">
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
                                    <!-- <input type="file"> -->
                                </div>
                                <button class="btn-primary" @click="submitRefundCancelOrder">ok</button>
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
    export default {
        name: "ModalRefundCancelOrder.vue",
        props: ['invoice'],
        data() {
            return {
                form : {
                    account_name : '',
                    account_number : '',
                    bank_name : '',
                    refund_type : '',
                    invoice_number : '',
                    identity_image: {}
                }
            }
        },
        methods: {
            close_modal(){
                $('.modal').fadeOut();
            },
            submitRefundCancelOrder(){
                const fd = new FormData();
                fd.append('invoice_number', this.invoice.invoice) 
                fd.append('account_name' , this.form.account_name) 
                fd.append('account_number', this.form.account_number) 
                fd.append('bank_name', this.form.bank_name) 
                fd.append('identity_image', this.form.identity_image) 
                fd.append('refund_type', this.form.refund_type) 

                apiCustomer.submitRefundCancelOrder(fd).then( response => {
                    if(response.data.code == 200){
                        window.location.reload()

                    }else{
                        //this.save_button = true;
                        alert('Silahkan isi semua field.')
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
                CloseModal
        }
    }
</script>

<style>

</style>
