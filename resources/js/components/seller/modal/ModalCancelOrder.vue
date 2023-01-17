<template>
    <div>
        <div id="cancel_order" class="modal"> 
            <div class="modal-dialog w-400">
                <div class="modal-body">
                    <span class="close-modal" @click="close_modal"></span>
                    <div class="content-modal">
                        <div class="detail-cancel">
                            <h4 class="text-center mtop-15">apakah anda ingin memproses pesanan ini?</h4>
                            <h5 v-if="cancel_confirm">  alasan menolak pemesanan</h5>
                            <textarea v-if="cancel_confirm" name="" id="" rows="7" v-model="reason"></textarea>
                            <small  v-if="cancel_confirm" >isi alasan penolakan pemesanan, jika ingin menolak pesanan</small>
                            <small style="color:red;">{{error_reason}}</small>
                            <div>
                                <button class="btn-secondary" @click="actionModal('cancel')">tolak pemesanan</button>
                                <button class="btn-primary" @click="actionModal('process')">terima pemesanan</button>
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
    name: "ModalCancelOrder.vue",
    props:['invoice'],
    data(){
        return {
            reason : "",
            error_reason : "",
            cancel_confirm : false
        }
    },
    methods : {
       close_modal(){
           this.cancel_confirm = false;
           $("#cancel_order").fadeOut(function () {
                $("#cancel_order").removeClass('overflow-scroll')
                $("body").removeClass('overflow-hidden');
            });
       },
       actionModal(action){
           if(action == 'cancel'){
               if(this.cancel_confirm == false){
                   return this.cancel_confirm = true;
               }
               if(this.reason == ''){
                   return this.error_reason = "Alasan harus diisi."
               }
               this.$emit('action' , {action : "cancel" , reason : this.reason});
           }
            if(action == 'process'){
               this.$emit('action' , {action : "process" , reason : this.reason});
           }
           this.error_reason = ''
           this.reason = ''
           this.close_modal();
       }
    }
}
</script>

<style>

</style>