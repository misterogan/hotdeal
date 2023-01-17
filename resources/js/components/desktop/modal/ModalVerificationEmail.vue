<template>
<div>
    <div id="modal_verification_email" class="modal">
        <div class="modal-dialog">
            <div class="modal-body">
                <span class="close-modal" @click="close_modal"></span>
                <div class="content-modal">
                    <h4>tambah nomor telepon</h4>
                    <div class="otp">
                        <div class="input-number">
                            <input class="email" type="email" name="email" :value="email"/>
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
    
</div>
</template>

<script>
import ModalOtpForm from '../modal/ModalOtpForm.vue'
import apiCustomer from '../../../apis/Customer'
export default {
    name: "ModalVerificationEmail.vue",
    props : ['email'],
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