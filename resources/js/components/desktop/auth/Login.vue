<template>
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
                            <img :src="!this.banner ? '/img/banner_login.svg' : this.banner" alt="">
                        </div>
                    </div>
                    <div class="col-6 section">
                        <h3>Masuk</h3>
                        <form v-on:submit.prevent="login()">
                            <div class="form-group">
                                <label>Email</label>
                                <div class="input-data">
                                    <input class="email" type="email" name="email" v-model="form_login.email" :class="error.email ? 'error' : '' " placeholder="Masukkan email anda">
                                    <!-- <small class="error" v-if="error.email">{{ error.email[0]}}</small> -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label>kata sandi</label>
                                <div class="input-data">
                                    <input id="pwd" class="password" :type="passwordFieldType" name="password" v-model="form_login.password" :class="error.email ? 'error' : '' " placeholder="Masukkan kata sandi anda">
                                    <img class="showhide" src="/img/assets_hotdeal_eye.svg" alt="" v-on:click="switchVisibility">
                                    <small class="error" v-if="error.password">{{ error.password[0]}}</small>
                                </div>
                            </div>
                            <div class="form-group error">
                                <small class="error" v-if="error.email">{{ error.email[0]}}</small>
                            </div>
                            <div class="form-group text-forget">
                                <a class="btn-link" href="/forget">Lupa kata sandi?</a>
                                <button class="btn-primary" type="submit" @click.prevent="login">Masuk</button>
                            </div>
                            <label class="mbottom-55 text-option">Atau Masuk Dengan</label>
                            <div class="option-button">
                                <!-- <GoogleLogin type="button" :params="params" :onSuccess="onSuccess" :onFailure="onFailure"><img src="img/login_google.svg" alt=""></GoogleLogin>-->
                                <!-- <facebook-login type="button"></facebook-login> -->
                                <button class="btn-google" type="button" @click="AuthProvider('google')" style="margin: 20px auto"></button>
                                <!-- <button class="btn-facebook" type="button" @click="AuthProvider('facebook')"></button> -->
                            </div>
                            <div class="float-right">
                                <label>Belum memiliki akun?</label>
                                <router-link class="btn-link" to="/register">daftar sekarang</router-link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </transition>
        <!-- <modal-alert :message="modal_message"></modal-alert> -->
        <modal-message></modal-message>
    </div>
</template>

<script>
    import Vue from 'vue'
    import User from '../../../apis/User'
    import Customer from '../../../apis/Customer'
    import axios from 'axios'
    import VueAxios from 'vue-axios'
    import VueSocialauth from 'vue-social-auth'
    import FacebookLogin from './FacebookLogin.vue'
    import ModalAlert from '../modal/ModalAlert.vue'
    import Message from '../../../utils/Message'
    import ModalMessage from '../modal/ModalMessage.vue'

    Vue.use(VueAxios, axios)
    Vue.use(VueSocialauth, {
        providers: {
            google: {
                clientId: '1049040768958-7pr33rjk3r05sdhi8d451rkd8fsc7p2d.apps.googleusercontent.com',
                redirectUri: 'https://hotdeal.id/api/login/google/callback'
            },
            facebook: {
                clientId: '379978210247237',
                redirectUri: 'https://hotdeal.id/api/login/facebook/callback'
            },
        }
    });

    export default {
        name: "Login",
        data(){
            return{
                form_login : {
                    email : '',
                    password : '',
                },
                google_login: {
                    name: '',
                    email: '',
                },
                error : {},
                params: {
                    client_id: "1049040768958-7pr33rjk3r05sdhi8d451rkd8fsc7p2d.apps.googleusercontent.com"
                },
                renderParams: {
                    width: 250,
                    height: 50,
                    longtitle: true
                },
                policylist : {},
                passwordFieldType:"password",
                banner : '',
                modal_message : '',
            }
        },
        mounted(){
            this.get_policy()
            this.get_banner()
        },
        methods: {
            get_policy(){
                this.error = {};
                Customer.get_privacy().then( response => {
                    this.policylist = response.data.data
                });
            },
            get_banner(){
                Customer.get_login_banner().then(response => {
                    this.banner = response.data.data.image
                })
            },
            login(){
                this.error = {};
                User.login(this.form_login).then(response => {
                    if (response.data.code == 200){
                        localStorage.setItem('auth','true');
                        this.form_login = {};
                        if(response.data.data.data.is_vendor == true){
                            return this.$router.push({name:'VendorDashboard'})
                        }else{
                            if(response.data.data.data.is_email_verified == false){
                                localStorage.setItem('verify-email',response.data.data.data.email);
                                return this.$router.push({name:'verified'})
                            }
                            this.is_login = true;
                        }
                         this.$router.push({name:'home'})
                    }else{
                        $("#alert_modal").fadeIn(function (){
                            $("body").addClass('overflow-hidden')
                        })
                        this.modal_message = response.data.errors.email[0];
                        Message.alert(response.data.errors.email[0],'Informasi');
                        this.error = response.data.errors;

                    }
                }).catch((errors) => {
                    this.error = errors.data.errors;
                });
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
                // localStorage.setItem('auth','true');
                // this.$router.push({name:'home'});
                axios.post('/api/login/'+provider, res)
                    .then(response => {
                        localStorage.setItem('auth','true');
                        this.$router.push({name:'home'});
                    }).catch(error => {
                       // alert("Terjadi kesalahan");
                })
            },
            LoginGoogle(provider){

                axios.get('/api/logins/'+provider)
                    .then(response => {
                        //localStorage.setItem('auth','true');
                        //this.$router.push({name:'home'});
                    }).catch(error => {
                })

                //  User.Googlelogin(provider).then(response => {
                //  }).catch(error => {
                //  })
            },
            onFailure(error) {
                console.log(error);
            },
            switchVisibility(){
                this.passwordFieldType =  this.passwordFieldType === "password" ? "text" : "password";
            },
        },
        components: {
            FacebookLogin, ModalAlert, Message, ModalMessage
        }
    }



</script>
