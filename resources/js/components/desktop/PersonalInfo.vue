<template>
    <div class="row-hd">
        <div class="col3-flex">
            <div class="flex-basis-40">
                <profile-menu></profile-menu>
                <profile-point-info style="display:block;"  @updatePicture="$emit('updatePicture' , $event)" v-if="Object.keys(profile).length > 0" :profile="profile.users"></profile-point-info>
            </div>
            <div class="flex-basis-60">
                <div class="box3-shadow-white">
                    <div v-if="is_loading">
                        <div class="ph-row justify-between mbottom-40">
                            <div class="d-block ph-col-2 bg-placeholder ph-h15"></div>
                        </div>
                        <div class="ph-row mbottom-35">
                            <div class="d-block ph-col-2 bg-placeholder ph-h15 mright-20"></div>
                            <div class="d-block ph-col-1 bg-placeholder ph-h15"></div>
                        </div>
                        <div class="ph-row mbottom-25">
                            <div class="ph-col-2 bg-placeholder ph-h15 mright-20"></div>
                            <div class="ph-col-4 bg-placeholder ph-h15"></div>
                        </div>
                        <div class="ph-row mbottom-25">
                            <div class="ph-col-2 bg-placeholder ph-h15 mright-20"></div>
                            <div class="ph-col-4 bg-placeholder ph-h15"></div>
                        </div>
                        <div class="ph-row mbottom-60">
                            <div class="ph-col-2 bg-placeholder ph-h15 mright-20"></div>
                            <div class="ph-col-4 bg-placeholder ph-h15"></div>
                        </div>
                        <div class="ph-row justify-between mbottom-35">
                            <div class="d-block ph-col-2 bg-placeholder ph-h15"></div>
                        </div>
                        <div class="ph-row mbottom-25">
                            <div class="ph-col-2 bg-placeholder ph-h15 mright-20"></div>
                            <div class="ph-col-4 bg-placeholder ph-h15"></div>
                        </div>
                        <div class="ph-row">
                            <div class="ph-col-2 bg-placeholder ph-h15 mright-20"></div>
                            <div class="ph-col-4 bg-placeholder ph-h15"></div>
                        </div>
                    </div>
                    <div v-if="!is_loading">
                        <h2 class="mbottom-30">Profil</h2>
                        <div class="mbottom-25" v-if="Object.keys(profile).length > 0">
                            <div class="row-100 mbottom-20">
                                <div class="col-3 col-sm-5 white-nowrap">
                                    <h5 class="fw-700 mright-30">biodata diri</h5>
                                </div>
                                <div class="col-9 col-sm-7">
                                    <router-link class="btn-link fw-700" to="/personal-info-edit/account">ubah biodata diri</router-link>
                                </div>
                            </div>
                            <div class="row-100 mbottom-15">
                                <div class="col-3 col-sm-5">
                                    <h5>nama</h5>
                                </div>
                                <div class="col-9 col-sm-7">
                                    <h5>{{profile.name}}</h5>
                                </div>
                            </div>
                            <div class="row-100 mbottom-15">
                                <div class="col-3 col-sm-5">
                                    <h5>tanggal lahir</h5>
                                </div>
                                <div class="col-9 col-sm-7">
                                    <h5>{{profile.dob}}</h5>
                                </div>
                            </div>
                            <div class="row-100 mbottom-30">
                                <div class="col-3 col-sm-5">
                                    <h5>jenis kelamin</h5>
                                </div>
                                <div class="col-9 col-sm-7">
                                    <h5>{{profile.gender === 'male' ? 'Pria' : 'Wanita'}}</h5>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="row-100 mbottom-20">
                                <div class="col-12">
                                    <h5 class="fw-700">kontak</h5>
                                </div>
                            </div>
                            <div class="row-100 mbottom-15 email">
                                <div class="col-3 col-sm-5">
                                    <h5>email</h5>
                                </div>
                                <div class="col-9 col-sm-7 d-flex align-center">
                                    <h5 class="mobile-ellipsis lowercase">{{profile.email}}</h5>
                                    <div class="verified-tag d-flex align-center mleft-10" v-if="profile.is_email_verified">
                                        <img class="mright-5" src="/img/assets_hotdeal_verified.svg" alt="" width="14">
                                        <h5 class="fs-12 fw-600">Telah diverifikasi</h5>
                                    </div>
                                    <div v-else>
                                        <h5 class="fs-12 fw-400 fp-pink">Belum Diverifikasi</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row-100 email">
                                <div class="col-3 col-sm-5">
                                    <h5>nomor hp</h5>
                                </div>
                                <div class="col-9 col-sm-7 d-flex align-center">
                                    <h5 class="mobile-ellipsis">{{profile.phone}}</h5>
                                    <div class="verified-tag d-flex align-center mleft-10" v-if="profile.is_phone_verified">
                                        <img class="mright-5" src="/img/assets_hotdeal_verified.svg" alt="" width="14">
                                        <h5 class="fp-black fs-12 fw-600">Telah diverifikasi</h5>
                                    </div>
                                    <div v-else>
                                        <h5 class="fw-700 fp-pink pointer" href="javascript:void(0)" v-if="!profile.is_phone_verified" @click="show_verify_otp('phone')">verifikasi</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <profile-phone :redirect="'personal-info'" :verified_value="verified_by.verified_value" :labels="verified_by.labels" :type="verified_by.type"></profile-phone>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import Vuelidate from 'vuelidate'
    Vue.use(Vuelidate)
    import apiCustomer from '../../apis/Customer'
    import User from '../../apis/User'
    import ProfileMenu from '../desktop/component/profile/ProfileMenu.vue'
    import ProfilePointInfo from '../desktop/component/profile/Profile.vue'
    import { required, minLength, between } from 'vuelidate/lib/validators'
    import ProfilePhone from '././modal/ProfilePhone.vue'

    export default {
        name: "PersonalInfo.vue",
        data(){
            return{
                profile : {},
                is_loading: true,
                error_phone_number_message : '',
                profile : {
                    phone : '',
                },
                form : {
                    phone_number : '',
                },
                verified_by : {
                    labels : '',
                    type : '',
                    verified_value : ''
                }
            }
        },
        components : {
            ProfileMenu,
            ProfilePointInfo,
            ProfilePhone
        },
        validations: {
            form : {
                 phone_number: {
                    required,
                    minLength: minLength(10)
                },
            }
        },
        mounted(){
            apiCustomer.profile().then( response => {
                this.profile = response.data
                this.is_loading= false
            });
            User.profile().then(response => {
                this.form.phone_number = this.profile.phone
            })
        },
        methods : {
            show_verify_otp(type){
                if(type == 'phone'){
                    this.verified_by.labels = 'Nomor Handphone'
                }
                $("#sending_otp").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
                // if(type == 'phone'){
                //     this.verified_by.labels = 'Nomor Handphone'
                //     this.verified_by.type = 'phone'
                //     this.verified_by.verified_value = this.form.phone_number
                //     let phone_number = this.form.phone_number;
                //     if(phone_number == null || phone_number.length < 11 ){
                //         return this.error_phone_number_message = "Format nomor telepon salah"
                //     }
                //     this.error_phone_number_message = ""
                //     apiCustomer.checkPhoneNumber({phone : this.form.phone_number}).then(response => {
                //         if(response.data.code == 200){
                //             $("#sending_otp").fadeIn(function () {
                //                 $("body").addClass('overflow-hidden');
                //             });
                //         }else{
                //             return this.error_phone_number_message = response.data.message
                //         }
                //     })
                // }else{
                //     this.verified_by.labels = 'Email' + ' ' + this.form.email
                //     this.verified_by.type = 'email'
                //     this.verified_by.verified_value = this.form.email
                //     $("#sending_otp").fadeIn(function () {
                //         $("body").addClass('overflow-hidden');
                //     });
                // }
            }
        }
    }
</script>