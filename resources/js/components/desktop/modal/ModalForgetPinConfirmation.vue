<template>
<div>
    <div id="forget_pin_modal_confirmation" class="modal">
        <div class="modal-dialog purple">
            <div class="modal-body">
                <span class="close-modal" @click="removeCheckbox()"></span>
                <div class="content-modal">
                    <div class="confirmation">
                        <div class="row">
                            <h5>apakah anda ingin mengubah pin sekarang?</h5>
                        </div>
                        <div class="row">
                            <div class="btn-option center">
                                <button class="m-auto" href="javascript:void(0)" @click="sendOTP">
                                    <h6>OK</h6>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="input_otp_forget_pin" class="modal">
        <div class="modal-dialog">
            <div class="modal-body">
                <span class="close-modal" @click="removeCheckbox()"></span>
                <div class="content-modal">
                    <h4>OTP telah dikirim ke email Anda</h4>
                    <div class="otp">
                        <div class="input-otp">
                            <input class="otp-pin" type="number" placeholder="kode verifikasi (OTP)">
                        </div>
                        <div class="btn-option">
                            <button class="cancel" @click="clearCheckbox()">
                                nanti saja
                            </button>
                            <button class="agree" href="javascript:void(0)" char="show_success_otp" @click="verify_otp">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <modal-alert :message="modal_message"></modal-alert>
</div>
</template>

<script>
    import CustomerAPi from '../../../apis/Customer'
    import CloseModal from '../component/CloseModal.vue'
    import apiCustomer from '../../../apis/Customer'
    import ModalAlert from './ModalAlert.vue'
    import Message from '../../../utils/Message'

    export default {
        name: "ModalForgetPinConfirmation.vue",
        data() {
            return {
                modal_message : ''
            }
        },
        methods: {
           sendOTP(){
            $("#modal_load").fadeIn(function () {
                //$("body").addClass('overflow-hidden');
            });
            const fd = new FormData();
            fd.append('status' , '123')
            apiCustomer.sendPinOTP(fd).then( response => {
                    if(response.data.code == 200){
                        $("#modal_load").fadeOut();
                        $("#forget_pin_modal_confirmation").fadeOut(function () {
                            $("body").addClass('overflow-hidden');
                        });
                        $("#input_otp_forget_pin").fadeIn(function () {
                           $("body").addClass('overflow-hidden');
                        });
                    }else{
                        console.log("tidak berhasil")
                    }
                });
            },
            close_modal(){
                $('.modal').fadeOut();
            },
            verify_otp(){
                const $otp = $('.otp-pin').val()
                const fd = new FormData();
                fd.append('otp' , $otp)
                apiCustomer.checkPinOTP(fd).then( response => {
                        if(response.data.code == 200){
                            $("#input_otp_forget_pin").fadeOut();
                            $("#create_pin_modal").fadeIn();
                        } else {
                             Message.alert(response.data.message,'Informasi');
                        }
                    });
            },
            removeCheckbox(){
                $('#check-point').prop('checked', false);
            },
            clearCheckbox(){
                $('#check-point').prop('checked', false);
                $('#input_otp_forget_pin').fadeOut();
            }
        },
        mounted(){
            
        },
        components:{
                CloseModal,
                apiCustomer,
                Message,
                ModalAlert
        }
    }
</script>

<style>

</style>