<template>
    <div>
        <div class="auth">
            <div class="header-single-page">
                <button onclick="history.back()" to="/" class="button-back"></button>
                <router-link to="/" class="button-home"><img src="/img/logo.svg" alt=""></router-link>
            </div>
            <transition name="fade" mode="out-in">
                <div id="wrap-homepage" class="container-form">
                    <div class="row-100">
                        <div class="col-6 banner">
                            <!-- <img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/static/login.png" alt="" width="100%"> -->
                            <div class="container-image">
                                <img src="/img/banner_login.svg" alt="">
                            </div>
                        </div>
                        <div class="col-6 section">
                            <!-- <h3>Daftar Sekarang</h3> -->
                            <form v-on:submit.prevent="emailRegister()" class="cs-form" v-show="show == 'verification-email'">
                                <div class="cs-form cs-form-head cs-text-center">
                                    <!-- <a href="/" class="button-home router-link-active"><img width="40%" src="/img/logo.svg" alt=""></a> -->
                                    <h3>Daftar Sekarang</h3>
                                </div>
                                <div class="cs-form">
                                    <label>Email:</label>
                                    <div class="input-data">
                                        <input class="email" type="email" :class="error.name ? 'error' : '' " name="email" v-model="form_register.email" placeholder="Masukkan email anda">
                                        <small class="cs-text-warning" v-if="error.email">{{ error.email}}</small>
                                    </div>
                                </div>
                                <div class="footer-form cs-text-center mtop-20">
                                    <button class="cs-btn-primary" type="submit">Buat Akun</button>
                                </div>
                            </form>

                            <form v-on:submit.prevent="verificationOtp()" class="cs-form cs-primary-color" v-show="show == 'verification-otp'">
                                <div class="cs-form cs-form-head cs-text-center">
                                    <!-- <a href="/" class="button-home router-link-active"><img width="40%" src="/img/logo.svg" alt=""></a> -->
                                    <h3> Masukkan Kode Verifikasi</h3>
                                </div>
                                <div class="cs-form cs-form-body">
                                    <label>Kode Verifikasi:</label>
                                    <div class="input-data">
                                        <input class="otp" type="text" :class="error.name ? 'error' : '' " name="otp" v-model="form_register.otp" placeholder="Masukkan kode OTP">
                                        <small class="cs-text-warning" v-if="error.otp">{{ error.otp}}</small>
                                    </div>
                                </div>
                                <div class="cs-text-center">
                                    <div class="cs-info mtop-20 f-12 cs-with-background">
                                        Kode verifikasi telah dikirim ke email {{form_register.email}}, segera cek email Anda.
                                    </div>
                                    <div class="mtop-20 text-question f-12">
                                        <span v-if="!show_send_again">Mohon tunggu dalam  <label class="label-with-action"> {{countDown}} detik</label>  untuk kirim ulang</span>
                                        <span  v-if="show_send_again">tidak menerima kode? <label class="mright-5 label-with-action" @click="emailRegister()" style="cursor: pointer">Kirim ulang</label></span>
                                    </div>
                                    <div class=" cs-form-footer mtop-20 cs-text-center">
                                        <button class="cs-btn-primary" type="submit">Verifikasi</button>
                                    </div>
                                </div>
                            </form>
                            <form v-on:submit.prevent="register()" class="cs-form cs-primary-color"   autocomplete="off" v-show="show == 'register-form'">
                                <div class="cs-form cs-form-head cs-text-center">
                                    <!-- <a href="/" class="button-home router-link-active"><img width="40%" src="/img/logo.svg" alt=""></a> -->
                                    <h3> Daftar dengan email </h3>
                                </div>
                                <div class="cs-form cs-form-body">
                                    <label>Email</label>
                                    <div class="input-data">
                                        <input type="text" role="presentation" autocomplete="off" name="register[email]" :value="form_register.email" placeholder="Email" disabled="disabled">
                                    </div>
                                </div>
                                <div class="cs-form cs-form-body mtop-10">
                                    <label>Nama Lengkap</label>
                                    <div class="input-data">
                                        <input type="text" role="presentation" autocomplete="off" name="register[name]"  v-model="form_register.name" placeholder="Masukkan Nama Anda">
                                    </div>
                                </div>
                                <div class="cs-form cs-form-body mtop-10">
                                    <label>Kata Sandi</label>
                                    <div class="input-data">
                                        <input type="password" autocomplete="off" name="register[password]" v-model="form_register.password" placeholder="Masukkan Kata Sandi">
                                    </div>
                                </div>
                                 <div class="cs-form cs-form-body mtop-10">
                                    <small class="cs-text-warning" v-if="error.register">{{ error.register}}</small>
                                </div>

                                <div class="cs-text-center">
                                    <div class="cs-info mtop-20 f-12">
                                       Dengan Mendaftar, saya menyetujui <router-link to="/policies?index=Aturan_dan_Kebijakan" target="_blank"> Syarat dan Ketentuan </router-link> serta <router-link to="/policies?index=Privacy_Policy" target="_blank">  Kebijakan Privasi</router-link>
                                    </div>
                                    <div class=" cs-form-footer mtop-20 cs-text-center">
                                        <button class="cs-btn-primary" type="submit">Selesai</button>
                                    </div>
                                </div>
                            </form>

                            <form v-on:submit.prevent="register()" v-show="show == 'verification-email'">
                                <div class="mtop-50">
                                    <label class="text-option" for="">daftar dengan : </label>
                                </div>
                                <div class="option-button register">
                                    <button class="btn-secondary btn-google" type="button" @click="AuthProvider('google')"></button>
                                    <!-- <button class="btn-secondary btn-facebook" type="button" @click="AuthProvider('facebook')"></button> -->
                                </div>
                                
                                <div class="agreement">
                                    <label class="agreement-check">Dengan mendaftar, saya menyetujui <router-link to="/policies?index=Aturan_dan_Kebijakan" target="_blank"> Syarat dan Ketentuan </router-link> serta <router-link to="/policies?index=Privacy_Policy" target="_blank">  Kebijakan Privasi</router-link></label>
                                </div>
                            
                            </form>
                            <div class="d-flex justify-center mtop-40 text-question">
                                <label class="mright-5">Sudah memiliki akun?</label>
                                <router-link class="btn-link" to="/login">masuk di sini</router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
         <profile-phone :redirectto="'login'" :verified_value="form_register.email" :type="'email'" :labels="'Email'"></profile-phone>
        <modal-loading></modal-loading>
    </div>
</template>

<script>
    import User from '../../../apis/User'
    import ProfilePhone from '../modal/ProfilePhone.vue';
    import ModalLoading from '../modal/ModalLoading.vue'
    export default {
        components: { ProfilePhone },
        name: "Register.vue",
        data(){
            return{
                btn_action : true,
                countDown : 0,
                send_again_in : 1,
                show_send_again : false,
                show : 'verification-email', // {verification-email => untuk pengecekan emal, verification-otp => verification form otp , register-form => form register}
                form_register : {
                    name : '',
                    email : '',
                    password :'',
                    password_confirmation : ''
                },
                error : {
                    email : '',
                    otp : '',
                    register : ''
                },
                passwordFieldType:"password",
                passwordFieldTypenew:"password"
            }
        },
        methods: {
            countDownTimer () {
                if (this.countDown > 0) {
                    setTimeout(() => {
                        this.countDown -= 1
                        if(this.countDown == 0 ){
                            this.show_send_again = true
                        }
                        this.countDownTimer()
                    }, 1000)
                }
            },
            register(){
                if(this.btn_action != true){
                    return ;
                }
                this.btn_action = false;
                this.error.register = '';
                User.register(this.form_register).then(response => {
                    if (response.data.code == 200 ){
                        if(response.data.message == 'success'){
                            localStorage.setItem('auth','true');
                            this.$router.push('/')
                        }else{
                            this.$router.push('/login')
                        }
                    }else{
                        this.error.register = response.data.message;
                    }
                })
                this.btn_action = true;
            },
            switchVisibility(){
                this.passwordFieldType =  this.passwordFieldType === "password" ? "text" : "password";
            },
            switchVisibilitynew(){
                this.passwordFieldTypenew =  this.passwordFieldTypenew === "password" ? "text" : "password";
            },
            emailRegister(){
                if(this.btn_action != true){
                    return ;
                }
                $("#modal_load").fadeIn();
                this.btn_action = false;
                this.error.email = '';
                this.show_send_again = false;
                User.emailRegister(this.form_register.email).then( response => {
                    if(response.data.code == 200){
                        this.countDown = 60;
                        this.show = 'verification-otp';
                        this.countDownTimer();
                    }else{
                        this.error.email = response.data.message;
                    }
                    $("#modal_load").fadeOut();
                    this.btn_action = true;
                })
            },
            verificationOtp(){
                if(this.btn_action != true){
                    return ;
                }
                this.btn_action = false;
                this.error.otp = '';
                User.VerifyOtp({otp : this.form_register.otp , form : this.form_register.email , type:'email'}).then( response => {
                    if(response.data.code == 200){
                        this.show = 'register-form';
                    }else{
                        this.error.otp = response.data.message
                    }
                    this.btn_action = true;
                })
            },
            AuthProvider(provider){
                var self = this;
                this.$auth.authenticate(provider)
                    .then(response => {
                        if(provider == 'google'){
                            self.socialLogin(provider,response)
                        }else{
                            localStorage.setItem('auth','true');
                            this.$router.push({name:'home'});
                        }
                    }).catch(error => {
                   // alert("Terjadi kesalahan");
                })
            },
            socialLogin(provider, res){
                User.social_login(provider,res)
                    .then(response => {
                        localStorage.setItem('auth','true');
                        // location.reload();
                        window.location.href = '/'
                    }).catch(error => {
                    //alert("Terjadi kesalahan");
                })
            },
            // verificationOtp(){
            //     User.VerifyOtp({otp : this.form_register.otp , form : this.form_register.email , type:'email'}).then( response => {
            //         if(response.data.code == 200){
            //             this.show = 'register-form';
                        
            //         }else{
                        
            //             //this.success_message = response.data.message
            //         }
            //     })
            // }
        },
        components: {
            ModalLoading
        }
    }
</script>