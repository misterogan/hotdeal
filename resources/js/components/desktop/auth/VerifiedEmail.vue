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
                    <div class="col-6 section">
                        <h3>Verifikasi Email</h3>
                        <form action="" method="POST" autocomplete="off">
                            <div class="form-group mtop-20">
                                <router-link class="btn-link" to="/policies?index=Aturan_&_Kebijakan">Kebijakan Privasi</router-link>
                                <button class="btn-primary" type="button" @click.prevent="send_otp">Verifikasi Email</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </transition>
        <profile-phone :redirectto="'login'" :verified_value="form.email" :type="'email'" :labels="'Email'"></profile-phone>
    </div>
</template>

<script>
    import Vue from 'vue'
    import User from '../../../apis/User'
    import axios from 'axios'
    import VueAxios from "vue-axios";
    import ProfilePhone from '../modal/ProfilePhone.vue';
    import apiCustomer from '../../../apis/Customer'

    Vue.use(VueAxios, axios)
    export default {
        name: "Forget",
        data() {
            return{
                form : {
                    email : '',
                },
                btn_otp : true,
                type : 'email'
            }
        },
        mounted(){
            if(localStorage.getItem('verify-email') != null){
                //alert(localStorage.getItem('verify-email'));
                this.form.email = localStorage.getItem('verify-email')
            }else{
                localStorage.removeItem('verify-email')
                this.$router.push({name:'login'})
            }
        },
        methods: {
            forgetPassword() {
                User.forget(this.form_forget).then(response => {
                    if (response.data.code == 200) {
                        this.step1 = false
                        this.step2 = true
                    } else {
                        this.error = response.data.errors;
                    }
                }).catch((errors) => {
                    this.error = errors.data.errors;
                });
            },
            show_modal_input_otp(){
                $("#input_otp").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },

            send_otp(){
                $("#sending_otp").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            
        },
        components: {
            ProfilePhone

        }
    }
</script>
