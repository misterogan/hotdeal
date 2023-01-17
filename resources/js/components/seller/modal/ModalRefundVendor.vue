<template>
    <div>
        <div id="refund_explanation" class="modal">
            <div class="modal-dialog w-500">
                <div class="modal-body">
                    <span class="close-modal" @click="close_modal"></span>
                    <div class="content-modal">
                        <div class="refund-vendor">
                            <div class="row">
                                <h4>alasan</h4>
                            </div>
                            <div class="row">
                                <textarea name="" id="" v-model="reason" cols="30" rows="5" placeholder="beri alasan"></textarea>
                            </div>
                            <div class="row">
                                <button v-if="action == 'approve' " class="btn-primary" @click="approveRefund">Terima</button>
                                <button v-if="action == 'reject' " class="btn-primary" @click="rejectRefund">Tolak</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="refund_confirm_admin" class="modal"> 
            <div class="modal-dialog w-500">
                <div class="modal-body">
                    <div class="content-modal">
                        <div class="refund-vendor">
                            <div class="row">
                                <h5 class="mbottom-10">anda telah menyetujui bahwa  barang telah diterima dan proses pengembalian Dana kepada user akan dilanjutkan oleh admin.</h5>
                            </div>  
                             <div class="row">
                                <textarea name="" id="" v-model="reason_confirm" cols="30" rows="5" placeholder="beri alasan"></textarea>
                            </div>
                            <div class="row">
                                <button  class="btn-primary" @click="approveConfirmRefund">Terima</button>
                                <button  class="btn-secondary " style="padding: 0px 50px 0px 50px;margin: auto;" @click="rejectConfirmRefund">Tolak</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CloseModal from '../../desktop/component/CloseModal.vue'
    import apiVendor from '../../../apis/Vendor'
    export default {
        data(){
            return {
                reason : '',
                button_approve : true,
                button_reject : true,
                error_reason : 'alasan harus diisi',
                reason_confirm : ''
            }
        },
        props :['invoice','action'],
        methods: {
            close_modal(){
                $('.modal').fadeOut();
                $("body").addClass('overflow-scroll');
            },
            approveRefund(){
                if(this.button_approve == true){
                    this.button_approve = false;
                        if(this.reason == ''){
                            this.error_reason = true;
                            this.button_approve = true;
                            return;
                        }
                        apiVendor.processRefundByInvoice({invoice : this.invoice , reason : this.reason}).then( response => {
                            this.button_approve = true;
                            window.location.reload()
                        })
                }  
            },
            rejectRefund(){
                if(this.button_reject == true){
                    this.button_reject = false;
                        if(this.reason == ''){
                            this.error_reason = true;
                            this.button_reject = true;
                            return;
                        }
                        apiVendor.rejectProcessRefundByInvoice({invoice : this.invoice , reason : this.reason}).then( response => {
                            this.button_reject = true;
                            window.location.reload()
                        })
                } 
            },
            approveConfirmRefund(){
                if(this.reason_confirm == ''){
                    return this.error_reason = true;
                }
                apiVendor.approveRefundByInvoice({invoice : this.invoice , reason : this.reason_confirm}).then( response => {               
                    window.location.reload()
                })
            },
            rejectConfirmRefund(){
                if(this.reason_confirm == ''){
                    return this.error_reason = true;
                }
                apiVendor.rejectProcessRefundByInvoice({invoice : this.invoice , reason : this.reason_confirm}).then( response => {
                    if(response.data.code === 200){
                        this.button_reject = true;
                        window.location.reload()
                    }
                })
            },
        },
        components:{
            CloseModal
        }
        
    }
</script>