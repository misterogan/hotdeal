<template>
    <div class="auth">
        <div class="header-single-page">
            <button onclick="history.back()" to="/" class="button-back"></button>
            <router-link to="/" class="button-home"><img src="/img/logo.svg" alt=""></router-link>
        </div>
        <transition name="fade" mode="out-in">
            <div class="container-form">
                <div class="row-100">
                    <div class="col-6 banner">
                        <!-- <img src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/static/login.png" alt="" width="100%"> -->
                        <div class="container-image">
                            <img src="/img/banner_login.svg" alt="">
                        </div>
                    </div>
                    <!-- <div class="col-6 section" v-if="step1">
                        <h3>pilih metode verifikasi</h3>
                        <form action="" method="POST" autocomplete="off">
                            <div class="form-group mbottom-40">
                                <label>Email</label>
                                <div class="input-data">
                                    <input class="email" type="email" name="email" v-model="form_forget.email" :class="error.email ? 'error' : '' " placeholder="masukkan email anda">
                                    <small class="error">{{error}}</small>
                                </div>
                            </div>
                            <div class="form-group mtop-20">
                                 <router-link class="btn-link" to="/policies?index=Aturan_dan_Kebijakan">Kebijakan Privasi</router-link>
                                <button class="btn-primary" type="button" @click.prevent="forgetPassword">Kirim Kode OTP</button>
                            </div>
                        </form>
                    </div> -->
                    <div class="col-6 section" v-if="step1">
                        <h3>masukkan kode verifikasi</h3>
                        <form action="" method="POST" autocomplete="off">
                            <div class="form-group mbottom-40">
                                <label>Kode Verifikasi</label>
                                <div class="input-data">   
                                    <input class="email" type="text" name="otp" v-model="form_forget.otp" :class="error.otp ? 'error' : '' " placeholder="masukkan kode OTP">
                                    <small class="error">{{error}}</small>
                                </div>
                            </div>
                            <div class="form-group mtop-20">
                                <button class="btn-primary" type="button" @click.prevent="registerVerify">Verifikasi</button>
                            </div>
                            <div class="box-info">
                                Kode OTP registrasi akun baru telah dikirim ke email, segera cek email Anda.
                            </div>
                            <div class="box-info" style="background-color: transparent; margin: 0px;">
                                Mohon tunggu dalam <b class="fp-purple">22 detik</b> untuk kirim ulang
                            </div>
                        </form>
                    </div>

                    <div class="col-6 section" v-if="step2" autocomplete="off">
                        <h3>Daftar dengan email</h3>
                        <form action="" method="POST" autocomplete="off">
                            <div class="form-group mbottom-20">
                                <label>Email</label>
                                <div class="input-data">
                                    <input class="email" type="email" name="email" v-model="form_forget.email" :class="error.email ? 'error' : '' " placeholder="masukkan email anda">
                                    <small class="error">{{error}}</small>
                                </div>
                            </div>
                            <div class="form-group mbottom-20">
                                <label>Nama Lengkap</label>
                                <div class="input-data">   
                                    <input class="name" type="text" name="name" v-model="form_forget.otp" :class="error.otp ? 'error' : '' " placeholder="masukkan nama lengkap anda">
                                    <small class="error">{{error}}</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Kata Sandi</label>
                                <div class="input-data">
                                    <input class="password" :type="passwordFieldType" :class="error.name ? 'error' : '' " name="password" v-model=" form_forget.otp" placeholder="Masukkan kata sandi">
                                    <img class="showhide" src="/img/assets_hotdeal_eye.svg" alt="" v-on:click="switchVisibility">
                                    <small class="error" v-if="error.password">{{ error.password[0]}}</small>
                                </div>
                            </div>
                            <div class="form-group mtop-20">
                                <button class="btn-primary" type="button" @click.prevent="registerVerify">Selesai</button>
                            </div>
                            <div class="box-info" style="background-color: transparent; margin: 0px;">
                                Saya telah membaca dan menyetujui kebijakan privasi <b class="fp-purple">hotdeal.id</b>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </transition>
        <modal-message></modal-message>
        <modal-loading></modal-loading>
        <modal-success-change></modal-success-change>
    </div>
</template>

<script>
    import Vue from 'vue'
    import User from '../../../apis/User'
    import axios from 'axios'
    import VueAxios from "vue-axios";
    import Message from '../../../utils/Message'
    import ModalMessage from '../modal/ModalMessage.vue';
    import ModalLoading from '../modal/ModalLoading.vue';
    import ModalSuccessChange from '../modal/ModalSuccessChange.vue'
    Vue.use(VueAxios, axios)

    export default {
        name: "Forget",
        data() {
            return{
                form_forget : {
                    email : '',
                    otp : '',
                    old_password : '',
                    new_password : '',
                },
                error : '',
                step1 : true,
                step2 : false,
                step3 : false,
                passwordFieldType:"password",
            }
        },
        methods: {
            show_success_change(){
                $("#modal_success_change").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            forgetPassword() {
                $("#modal_load").fadeIn(function () {});
                this.error = ''
                User.forget(this.form_forget).then(response => {
                     $("#modal_load").fadeOut();
                    if (response.data.code == 200) {
                        this.step1 = false
                        this.step2 = true
                    } else {
                        // this.error = response.data.message;
                        this.error = 'Email yang Anda masukan tidak terdaftar. Silahkan Coba Lagi ';
                    }
                }).catch((errors) => {
                    this.error = response.data.message;
                });
            },
            registerVerify() {
                this.error = ''
                this.step2 = true
                this.step1 = false
            },
            forgetSet() {
                this.error = ''
                User.forgetSet(this.form_forget).then(response => {
                    if (response.data.code == 200) {
                        // Message.alert('Password telah diperbaharui.' , 'Informasi' , 1500 )
                        this.show_success_change()
                        setTimeout(function() {
                           window.location.href = '/login'
                        }, 1500);
                    } else {
                        this.error = response.data.errors;
                    }
                }).catch((errors) => {
                    this.error = errors.data.errors;
                });
            },
            switchVisibility(){
                this.passwordFieldType =  this.passwordFieldType === "password" ? "text" : "password";
            },
        },
        components: {
            Message,
            ModalMessage,
            ModalLoading,
            ModalSuccessChange
        }
    }
</script>
