<template>
    <div>
        <div id="detail_refund" class="modal">
            <div class="modal-dialog w-400">
                <div class="modal-body">
                    <span class="close-modal" @click="close_modal"></span>
                    <div class="content-modal">
                        <div class="detail-refund">
                            <h3>Form refund</h3>
                            <div class="row align-center mbottom-15 justify-between">
                                <div class="status">refund</div>
                                <h6>tanggal pengajuan {{refund.created_at}}</h6>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h6>no invoice</h6>
                                    <h6>{{refund.invoice_number}}</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h6>opsi refund</h6>
                                    <h6>{{refund.refund_type}}</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h6>alasan pengembalian</h6>
                                    <h6>{{refund.description}}</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h6>foto</h6>
                                    <div class="product-image">
                                        <div class="container-img">
                                            <img :src="refund.image_1 != '' ? refund.image_1   : '/img/150x150.svg'" alt="">
                                        </div>
                                        <div class="container-img">
                                            <img :src="refund.image_2 != '' ? refund.image_2   : '/img/150x150.svg'" alt="">
                                        </div>
                                        <div class="container-img">
                                            <img :src="refund.image_3 != '' ? refund.image_3   : '/img/150x150.svg'" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h6>video</h6>
                                    <div class="product-image">
                                        <div class="container-img" v-if="refund.video == ''">
                                            <img src="/img/150x150.svg" alt="">
                                        </div>
                                        <div class="container-img" v-else>
                                            <video :src="refund.video" width="320" height="240" controls>      
                                            </video>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="Object.keys(refund).length > 0 && refund.status == 'waiting'">
                                <div class="row">
                                    <div class="col-12">
                                        <h6>alasan</h6>
                                        <textarea v-model="reason" name="" id="" cols="10" rows="4"></textarea>
                                        <label v-if="error_reason" style="color:red;">Alasan refund wajib diisi</label>
                                    </div>
                                </div>
                                <div class="row" >
                                    <button class="btn-secondary" @click="rejectRefund">reject</button>
                                    <button class="btn-primary" @click="approveRefund">approve</button>
                                </div>
                            </div>
                            <div v-else>
                                <div class="row">
                                    <button class="btn-secondary" @click="close_modal">Close</button>
                                </div>
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
    name: "ModalDetailRefund.vue",
    data(){
        return {
            reason : '',
            error_reason : false,
            button_approve : true,
            button_reject : true,
        }
    },
    props:['refund'],
    mounted(){
        
    },
    methods : {
       close_modal(){
           $("#detail_refund").fadeOut(function () {
                $("#detail_refund").removeClass('overflow-scroll')
                $("body").removeClass('overflow-hidden');
            });
       },
       approveRefund(){
           if(this.button_approve == true){
               this.button_approve = false;
                if(this.reason == ''){
                    this.error_reason = true;
                    this.button_approve = true;
                    return;
                }
                apiVendor.processRefundByInvoice({invoice : this.refund.invoice_number , reason : this.reason}).then( response => {
                    this.button_approve = true;
                    this.close_modal();
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
                apiVendor.rejectProcessRefundByInvoice({invoice : this.refund.invoice_number , reason : this.reason}).then( response => {
                    this.button_reject = true;
                    this.close_modal();
                     window.location.reload()
                })
           } 
       }
    }
}
</script>

<style>

</style>