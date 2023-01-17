<template>
    <div>
        <div id="approve_refund" class="modal">
            <div class="modal-dialog w-400">
                <div class="modal-body">
                    <span class="close-modal" @click="close_modal"></span>
                    <div class="content-modal">
                        <div class="detail-refund">
                            <h3>Konfirmasi Setujui pengembalian</h3>
                            <div class="row">
                                    <div class="col-12">
                                        <h6>alasan</h6>
                                        <textarea v-model="reason" name="" id="" cols="10" rows="4"></textarea>
                                        <label v-if="error_reason" style="color:red"> Alasan wajib diisi.</label>
                                    </div>
                                </div>
                            <div class="row" >
                                <button class="btn-secondary" @click="rejectRefund">reject</button>
                                <button class="btn-primary" @click="approveRefund">approve</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import apiVendor from '../../../apis/Vendor'
export default {
    name: "ModalApproveRefund.vue",
    data(){
        return {
            reason : '',
            error_reason : false,
            button_approve : true,
            button_reject : true,
        }
    },
    props:['invoice'],
    mounted(){
        
    },
    methods : {
       close_modal(){
           $("#approve_refund").fadeOut(function () {
                $("#approve_refund").removeClass('overflow-scroll')
                $("body").removeClass('overflow-hidden');
            });
       },
       approveRefund(){
           if(this.reason == ''){
               return this.error_reason = true;
           }
           apiVendor.approveRefundByInvoice({invoice : this.invoice , reason : this.reason}).then( response => {               
               this.close_modal()
               window.location.reload()
           })
       },
       rejectRefund(){
           if(this.button_reject == true){
               this.button_reject = false;
                if(this.reason == ''){
                    this.error_reason = true;
                    this.button_reject = true;
                    return
                }
                apiVendor.rejectProcessRefundByInvoice({invoice : this.invoice , reason : this.reason}).then( response => {
                    this.button_reject = true
                    this.close_modal()
                    window.location.reload()
                })
           } 
       }
    }
}
</script>

<style>

</style>