<template>
<div>
    <div id="otp_warning_modal" class="modal">
        <div class="modal-dialog purple">
            <div class="modal-body">
                <span class="close-modal" @click="close_modal"></span>
                <div class="content-modal">
                    <div class="confirmation">
                        <div class="row">
                            <h5>mohon verifikasi no.telepon anda sebelum checkout. apakah anda ingin verifikasi sekarang?</h5>
                        </div>
                        <div class="row">
                            <div class="btn-option center">
                                <button class="m-auto" href="javascript:void(0)" @click="show_input_otp">
                                    <h6>OK</h6>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="input_otp_checkout" class="modal">
        <div class="modal-dialog">
            <div class="modal-body">
                <span class="close-modal" @click="close_modal"></span>
                <div class="content-modal">
                    <h4>tambah nomor telepon</h4>
                    <div class="otp">
                        <div class="input-number">
                            <input type="number" min="11"  v-model="phone_number" placeholder="Nomor Telepon">
                            <button class="agree" @click="send_otp">{{btn_label}} <span v-if="show_count_down">( {{count_down_otp}} )</span></button>
                            <small class="fp-pink" v-if="required_phone">{{required_phone_msg}}</small>
                        </div>
                        <div class="input-otp">
                            <input type="number" v-model="otp" placeholder="kode verifikasi (OTP)">
                            <small class="fp-pink fs-11 fw-400">{{error_message_otp}}</small>
                            <small class="fp-pink fs-11 fw-400">{{success_message}}</small>
                        </div>
                        <div class="btn-option">
                            <button class="cancel" @click="close_modal">
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
    <div id="otp_success" class="modal">
        <div class="modal-dialog w-350">
            <div class="modal-body">
                <div class="content-modal">
                    <div class="notify-modal">
                        <img class="ilustrasi" src="/img/ilustrasi_sukses.svg">
                        <h5 class="mtop-20">selamat no anda telah diverifikasi!</h5>
                        <button @onclick="close_modal" class="cont-shopping btn-primary">lanjutkan berbelanja</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import ModalOtpForm from '../modal/ModalOtpForm.vue'
import apiCustomer from '../../../apis/Customer'
export default {
    name: "ModalOtp.vue",
    props : [],
    data() {
        return {
            otp : '',
            phone_number : '',
            btn_verify : false,
            btn_otp : true,
            count_down_otp : 100,
            show_count_down : false,
            required_phone_msg : 'Lengkapi Nomor Telepon',
            required_phone : false,
            error_message_otp : '',
            timer : false,
            success_message : '',
            btn_label : 'kirim kode verifikasi (OTP)'

        }
    },
    mounted(){
        
    },
    methods: { 
        close_modal(){
            $('.modal').fadeOut();
            $("body").removeClass('overflow-hidden');
        },
        show_input_otp(){
            $("#otp_warning_modal").fadeOut(function () {
               $("body").addClass('overflow-hidden');
               
            });
            $("#input_otp_checkout").fadeIn(function () {
                $("body").addClass('overflow-hidden');
            });
            
        },
        show_success_otp(){
            $("#otp_success").fadeIn(function () {
                $("body").addClass('overflow-hidden');
            });
            $("#input_otp_checkout").fadeOut(function () {
                $("body").addClass('overflow-hidden');
            });
        },
        send_otp(){
            let phone_number = this.phone_number;
            if(phone_number.length < 11 ){
                this.required_phone = true;
                return;
            }
            if(this.btn_otp === true){
                this.btn_otp = false;
                apiCustomer.sendOtp({form : this.phone_number , type : 'phone'}).then( response => {
                    if(response.data.code === 200){
                        this.btn_verify = true
                        this.show_count_down = true
                        this.CountDownOtp()
                        this.btn_label = 'Kirim ulang dalam'
                        this.success_message = response.data.data.msg
                    }else{
                         this.success_message = response.data.message
                    }
                })
            }
            
        },
        CountDownOtp(){
           const TIME_COUNT = 100;
            if (!this.timer) {
                this.count_down_otp = TIME_COUNT;
                this.show_count_down = true;
                this.timer = setInterval(() => {
                if (this.count_down_otp > 0 && this.count_down_otp <= TIME_COUNT) {
                        this.count_down_otp--;
                    } else {
                        
                        this.show_count_down = false;
                        this.btn_otp = true;
                        this.btn_label = 'kirim kode verifikasi (OTP)'
                    clearInterval(this.timer);
                        this.timer = false;
                    }
                }, 1000)
            }
        },
        verify_otp(){
            if(this.btn_verify != true){
                return this.success_message = "Silahkan kirim dahulu OTP."
            }
            apiCustomer.VerifyOtp({otp : this.otp , form : this.phone_number , type:'phone'}).then( response => {
                 if(response.data.code === 200){
                    this.btn_verify = false
                    this.show_success_otp()
                 }else{
                     this.success_message = response.data.message
                 }
            })
        }
    },
    mounted(){
        
    },
    components:{
        ModalOtpForm
    }
}
</script>

<style>

</style>