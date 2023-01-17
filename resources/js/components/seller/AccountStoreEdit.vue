<template>
    <div>
        <div class="main-content">
            <div class="center-menu" style="width:100%">
                <div class="main-menu">
                    <div class="box address-store">
                            <h3>akun</h3>
                            <div class="input-data">
                                <form action="">
                                    <h5>user name</h5>
                                    <input type="text" name="name" v-model="$v.form.name.$model">
                                </form>
                                <form action="">
                                    <h5>nomor telepon</h5>
                                    <input type="text" name="phone_number" v-model="$v.form.phone_number.$model">
                                </form>
                                <form action="">
                                    <h5>email</h5>
                                    <input type="email" name="email" v-model="$v.form.email.$model">
                                </form>
                                <div class="button">
                                    <button class="btn-primary-seller">
                                        <a v-on:click="saveProfile" >simpan & tampilkan</a>
                                    </button>
                                </div>
                                <div class="input-pwd">
                                    <form action="">
                                        <h5>password saat ini</h5>
                                        <input type="password" name="oldpass" v-model="form.oldpass">
                                    </form>
                                    <form action="">
                                        <h5>password baru</h5>
                                        <input type="password" name="newpass" v-model="form.newpass">
                                    </form>
                                    <form action="">
                                        <h5>konfirmasi password</h5>
                                        <input type="password" name="confirm" v-model="form.confirm">
                                    </form>
                                </div>
                                <small class="error" style="color: red;"> {{error}}</small>
                                <div class="button">
                                    <button class="btn-primary-seller">
                                        <a v-on:click="change_password" >ubah password</a>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <!-- <modal></modal> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import Vuelidate from 'vuelidate'
    Vue.use(Vuelidate)
    import Vendor from '../../apis/Vendor'
    import User from '../../apis/User'
    import apiCustomer from '../../apis/Customer'
    import { required, minLength, between } from 'vuelidate/lib/validators'
    import ModalLoad from '../desktop/modal/ModalLoading.vue'

    export default {
        name: "AccountStoreEdit.vue",
        data(){
            return {
                page : this.$route.name,
                error_phone_number_message : '',
                profile : {
                    name : '',
                    email : '',
                    phone : '',
                },
                form : {
                    name : '',
                    email : '',
                    phone_number : '',
                    oldpass : '',
                    newpass :''
                },
                verified_by : {
                    labels : '',
                    type : '',
                    verified_value : ''
                },
                error : '',
            }
        },
        validations: {
            form : {
                name: {
                    required,
                    minLength: minLength(4),
                },
                phone_number: {
                    required,
                    minLength: minLength(10)
                },
                email: {
                    required,
                    minLength: minLength(10)
                },
            },

        },
        mounted (){
            User.profile().then(response => {
                this.profile = response.data;
                this.form.name = this.profile.name
                this.form.phone_number = this.profile.phone
                this.form.email = this.profile.email
            })
        },
        methods : {
            saveProfile(){
                $("#modal_load").fadeIn();
                Vendor.updateProfile(this.form).then(response => {
                    if(response.data.code == 200){
                        $("#modal_load").fadeOut();
                        this.profile = response.data.data
                        this.$router.push({path :'/vendor/store/account'})
                    }
                })
            },
            change_password(){
                $("#modal_load").fadeIn();
                apiCustomer.change_password(this.form).then(response => {
                    if(response.data.code == 200){
                        this.profile = response.data.data
                        this.$router.push({path :'/vendor/store/account'})
                    }else{
                        this.error = response.data.message;
                    }
                    $("#modal_load").fadeOut();
                })
            },
        },
        components : {
            ModalLoad
        }
    }
</script>

<style scoped>

</style>