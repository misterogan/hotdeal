<template>
<div>
    <div id="sending_otp" class="modal">
            <div class="modal-dialog w-300">
                <div class="modal-body">
                    <div class="content-modal">
                        <div class="notify-modal sending">
                            <h5 class="notify-text mbottom-10">Tambah no handphone anda</h5>
                            <img class="ilustrasi" src="/img/ilustrasi_otp.svg">
                            <input class="mbottom-15" type="number" placeholder="ketik no handphone anda" @keyup="error_message = ''" v-model="phone_number">
                            <h5 class="text-center fw-600" style="color: #505050;">Kami akan mengirimkan kode verifikasi melalui no handphone anda</h5>
                            <small class="error notify-text " style="color:red;">{{error_message}}</small>
                            <button class="btn-primary notify-button" href="javascript:void(0)" @click="send_otp('phone')">kirim kode verifikasi</button>
                            <a href="javascript:void(0)" @click="cancel_modal()" class="btn-link notify-button m-auto d-flex justify-center text">Nanti saja</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="input_otp" class="modal">
            <div class="modal-dialog otp w-350">
                <div class="modal-body">
                    <div class="content-modal">
                        <div class="notify-modal">
                            <h5 class="notify-text">kode verifikasi telah terkirim</h5>
                            <h5 class="notify-text">Masukkan kode verifikasi yang kami kirim ke {{labels}} kamu</h5>
                            <div class="input">
                                <div class="input-otp">
                                    <input id="otp_partitioned" class="partitioned" type="text" maxlength="6" v-model="otp" @change="remove_error" />
                                </div>
                            </div>
                            <p class="error">{{error_message_otp}}</p>
                            <p>tidak menerima kode verifikasi?</p>
                            <div class="retry">
                                <button class="btn-link" v-if="count_down_otp > 0">
                                    kirim ulang dalam ( {{count_down_otp}} )
                                </button>
                                <button v-else class="btn-link" @click="send_otp">
                                    Kirim ulang OTP
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
                        <h5 class="notify-text">selamat {{labels}} anda telah diverifikasi!</h5>
                        <img class="ilustrasi" src="/img/assets_success_phone.svg">
                        <button class="btn-primary m-auto" @click="close_modal()">lanjut belanja</button>
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
    name: "ProfilePhone.vue",
    props : ['verified_value' , 'labels','type' ,'redirect'],
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
            error_message : '',
            btn_label : 'kirim kode verifikasi (OTP)'

        }
    },
    watch : {
        otp: function(val){
            if(val.length === 6){
                this.verify_otp()
            }
        }
    },
    mounted(){
        this.partitioned_otp();
    },
    methods: { 
        // watch : {
        //     phone_number(val){
        //         if(val != this.phone_number){
        //             this.error_message = ''
        //         }
        //     }
        // },
        partitioned_otp(){
            var obj = document.getElementById('otp-partitioned');
            obj.addEventListener('keydown', stopCarret); 
            obj.addEventListener('keyup', stopCarret); 

            function stopCarret() {
                if (obj.value.length > 3){
                    setCaretPosition(obj, 3);
                }
            }

            function setCaretPosition(elem, caretPos) {
                if(elem != null) {
                    if(elem.createTextRange) {
                        var range = elem.createTextRange();
                        range.move('character', caretPos);
                        range.select();
                    }
                    else {
                        if(elem.selectionStart) {
                            elem.focus();
                            elem.setSelectionRange(caretPos, caretPos);
                        }
                        else
                            elem.focus();
                    }
                }
            }
        },
        close_modal(){
            $('#otp_success').fadeOut()
        },
        show_success_otp(){
                $("#input_otp").fadeOut(function () {
                    $("body").addClass('overflow-hidden');
                });
                $("#otp_success").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });

            },
        show_modal_input_otp(){
            $("#sending_otp").fadeOut(function () {
                $("body").addClass('overflow-hidden');
            });
            $("#input_otp").fadeIn(function () {
                $("body").addClass('overflow-hidden');
            });
        },
        send_otp(type=''){
            if(type != ''){
                this.type = type;
            }
            if(this.type == 'phone'){
                if(this.phone_number.length < 11 ){
                    this.required_phone = true;
                    this.error_message = 'Nomor telepon minimal 10 angka';
                    return;
                }
            }
            if(this.btn_otp === true){
                this.btn_otp = false;
                apiCustomer.sendOtp({form : this.phone_number , type : this.type}).then( response => {
                    if(response.data.code === 200){
                        this.btn_verify = true
                        this.show_count_down = true
                        this.CountDownOtp()
                        this.btn_label = 'Kirim ulang dalam'
                        this.success_message = response.data.data.msg
                        this.show_modal_input_otp()
                    }else{
                        this.btn_otp = true;
                        this.error_message = response.data.message
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
            apiCustomer.VerifyOtp({otp : this.otp , form : this.phone_number , type : this.type}).then( response => {
                 if(response.data.code === 200){
                    this.btn_verify = false

                    this.show_success_otp()
                    setInterval(function(){ 
                        location.href = '/'
                    }, 4000);
                 }else{
                     this.error_message_otp = response.data.message
                 }
            })
        },

        remove_error(){
            this.success_message = ''
            this.error_message_otp = ''
        },
        cancel_modal(){
            $("#sending_otp").fadeOut(function () {
                $("body").removeClass('overflow-hidden');
            });
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