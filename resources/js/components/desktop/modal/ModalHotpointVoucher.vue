<template>
    <div id="modal_hotvoucher" class="modal">
        <div class="modal-dialog w-300">
            <div class="modal-body">
                <div class="content-modal" v-if="Object.keys(hotpoint_data).length > 0">
                    <div class="notify-modal hotpoint-voucher">
                        <div class="mbottom-40 title">nominal vouchermu</div>
                        kode voucher : 
                        <b  class="mbottom-10">{{hotpoint_data.voucher_code}}</b>
                        <div class="input-disabled">
                            Nominal 
                            <b> {{hotpoint_data.value | NumberFormat}}</b>
                        </div>
                        <button class="btn-primary" @click="claimHotpoint">tambahkan ke hot point</button>
                        <button class="btn-link mtop-15" @click="cancel">nanti saja</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template> 

<script>
    export default {
        name: "ModalHotpointVoucher.vue",
        props: ['hotpoint_data'],
        data(){
            return {

            }
        },
        methods:{
            claimHotpoint(){
                $("#modal_load").fadeIn();
                this.$emit('action', 'claim')
            },
            cancel(){
                this.$emit('action', 'cancel')
            },

        }
    }
</script>

<style>
</style> 