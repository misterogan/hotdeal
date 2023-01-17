<template>
    <div class="flex-basis-60">
        <div class="box3-shadow-white edit-profile">
            <h2 class="mbottom-40">Profil</h2>
            <h4 class="mbottom-25">biodata diri</h4>
            <div class="row-100 mbottom-15">
                <div class="col-3 col-sm-12">
                    <h5>Nama</h5>
                </div>
                <div class="col-9 col-sm-12">
                    <input type="text" v-model="$v.form.name.$model">
                </div>
            </div>
            <div class="row-100 mbottom-20">
                <div class="col-3 col-sm-12">
                    <h5>Tanggal Lahir</h5>
                </div>
                <div class="col-9 col-sm-12">
                    <input type="date" v-model="$v.form.date_of_birth.$model">
                </div>
            </div>
            <div class="row-100">
                <div class="col-3 col-sm-12">
                    <h5 class="mbottom-10">Jenis Kelamin</h5>
                </div>
                <div class="col-9 col-sm-12">
                    <div class="option-gender">
                        <div class="d-flex align-center">
                            <div class="radio">
                                <input v-if="form.gender == 'male'" v-model="$v.form.gender.$model" type="radio" value="male" selected />
                                <input v-else v-model="$v.form.gender.$model" type="radio" value="male" name='gender' id='male' />
                                <label for="male">
                                    <span class="radio-text mleft-25">Pria</span>
                                </label>
                            </div>
                        </div>
                        <div class="d-flex align-center">
                            <div class="radio">
                                <input v-if="form.gender == 'female'" v-model="$v.form.gender.$model" type="radio" value="female" selected />
                                <input v-else v-model="$v.form.gender.$model" type="radio" value="female" name='gender' id='female' />
                                <label for="female">
                                    <span class="radio-text mleft-25">Wanita</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn-confirm mtop-35">
                <button class="btn-secondary">
                    <router-link :to="'/personal-info'">Batal</router-link>
                </button>
                <button class="btn-primary">
                    <a v-on:click="saveProfile" >simpan</a>
                </button>
            </div>
        </div>
        <profile-phone :redirect="'personal-info'" :verified_value="verified_by.verified_value" :labels="verified_by.labels" :type="verified_by.type"></profile-phone>
    </div>
</template>

<script>
    import Vue from 'vue'
    import Vuelidate from 'vuelidate'
    Vue.use(Vuelidate)
    import apiCustomer from '../../../../apis/Customer'
    import User from '../../../../apis/User'
    import ProfileMenu from './ProfileMenu.vue'
    import ProfilePointInfo from './Profile.vue'
    import { required, minLength, between } from 'vuelidate/lib/validators'
    import ProfilePhone from '../../modal/ProfilePhone.vue'
    
    export default {
        name: "AccountForm.vue",
        data(){
            return {
                page : this.$route.name,
                error_phone_number_message : '',
                profile : {
                    name : '',
                    email : '',
                    email_verified_at : false,
                    dob : '',
                    gender :'',
                    phone : '',
                },
                form : {
                    name : '',
                    email : '',
                    email_verified_at : false,
                    date_of_birth : '',
                    gender :'',
                    phone_number : '',
                },
                verified_by : {
                    labels : '',
                    type : '',
                    verified_value : ''
                }
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
                date_of_birth: {
                    required,
                },
                gender: {
                    required,
                },
            },

        },
        mounted (){
            User.profile().then(response => {
                this.profile = response.data;
                this.form.name = this.profile.name
                this.form.phone_number = this.profile.phone
                this.form.date_of_birth = this.profile.dob
                this.form.email = this.profile.email
                this.form.gender = this.profile.gender
            })
        },
        methods : {
            saveProfile(){
                $("#modal_load").fadeIn(function () {
                   $("body").addClass('overflow-hidden');
                });
                apiCustomer.saveProfile(this.form).then(response => {
                    if(response.data.code == 200){
                        $("body").removeClass('overflow-hidden');
                        $("body").addClass('overflow-scroll');
                        $("#modal_load").fadeOut();
                        this.profile = response.data.data
                        this.$router.push({path :'/personal-info'})
                       // 
                    }
                })
            },
            show_verify_otp(type){
                if(type == 'phone'){
                    this.verified_by.labels = 'Nomor Handphone'
                    this.verified_by.type = 'phone'
                    this.verified_by.verified_value = this.form.phone_number
                    let phone_number = this.form.phone_number;
                    if(phone_number == null || phone_number.length < 11 ){
                        return this.error_phone_number_message = "Format nomor telepon salah"
                    }
                    this.error_phone_number_message = ""
                    apiCustomer.checkPhoneNumber({phone : this.form.phone_number}).then(response => {
                        if(response.data.code == 200){
                            $("#sending_otp").fadeIn(function () {
                                $("body").addClass('overflow-hidden');
                            });
                        }else{
                            return this.error_phone_number_message = response.data.message
                        }
                    })
                }else{
                    this.verified_by.labels = 'Email' + ' ' + this.form.email
                    this.verified_by.type = 'email'
                    this.verified_by.verified_value = this.form.email
                    $("#sending_otp").fadeIn(function () {
                        $("body").addClass('overflow-hidden');
                    });
                }
            }
        },
        components : {
            ProfileMenu,
            ProfilePointInfo,
            ProfilePhone
        }
    }
</script>

<style scoped>

</style>
