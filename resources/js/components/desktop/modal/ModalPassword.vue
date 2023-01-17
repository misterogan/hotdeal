<template>
    <div id="password_modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-body">
                <span class="close-modal"></span>
                <div class="content-modal">
                    <h4 class="mbottom-25">Keamanan</h4>
                    <h5 class="mbottom-15">pengaturan keamanan</h5>
                    <div class="password-modal" v-if="hasPassword">
                        <div class="row-100 align-center mbottom-10">
                            <div class="col-4">
                                <h5 class="fw-600">Kata Sandi Lama</h5>
                            </div>
                            <div class="col-8">
                                <div class="row-100 justify-between password-input">
                                    <input class="password" placeholder="Masukkan Kata Sandi Lama" name="oldpass" id="oldpass" :type="passwordFieldType" v-model="form.oldpass">
                                    <img class="showhide" src="/img/assets_hotdeal_eye.svg" alt="" v-on:click="switchVisibility">
                                </div>
                            </div>
                        </div>
                        <div class="row-100 align-center mbottom-10">
                            <div class="col-4">
                                <h5 class="fw-600">Kata Sandi Baru</h5>
                            </div>
                            <div class="col-8">
                                <div class="row-100 justify-between password-input">
                                    <input class="password-new" placeholder="Masukkan Kata Sandi Baru" :type="passwordFieldTypenew" name="newpass" id="newpass" v-model="form.newpass">
                                    <img class="showhide-new" src="/img/assets_hotdeal_eye.svg" alt="" v-on:click="switchVisibilitynew">
                                </div>
                            </div>
                        </div>
                        <div class="row-100 align-center mbottom-10">
                            <div class="col-4">
                                <h5 class="fw-600">Konfirmasi Kata sandi</h5>
                            </div>
                            <div class="col-8">
                                <div class="row-100 justify-between password-input">
                                    <input class="password-confirm" placeholder="Konfirmasi Kata Sandi Baru" :type="passwordFieldTypeConfirmnew" name="confirm" id="confirm" v-model="form.confirm">
                                    <img class="showhide-confirm" src="/img/assets_hotdeal_eye.svg" alt="" v-on:click="switchVisibilityConfirmnew">
                                </div>
                                 <small class="error fp-pink"> {{error}} </small>
                            </div>
                        </div>
                    </div>
                    <div v-else >
                        <div class="row-100 align-center mbottom-10">
                            <div class="col-4">
                                <h5 class="fw-600">Kata sandi</h5>
                            </div>
                            <div class="col-8">
                                <div class="row-100 justify-between password-input">
                                    <input class="password" placeholder="Masukkan Kata Sandi" name="oldpass" id="oldpass" :type="passwordFieldType" v-model="form.oldpass">
                                    <img class="showhide" src="/img/assets_hotdeal_eye.svg" alt="" v-on:click="switchVisibility">
                                </div>
                            </div>
                        </div>
                        <div class="row-100 align-center mbottom-10">
                            <div class="col-4">
                                <h5 class="fw-600">Konfirmasi kata sandi</h5>
                            </div>
                            <div class="col-8">
                                <div class="row-100 justify-between password-input">
                                    <input class="password-confirm" placeholder="Konfirmasi Kata Sandi" :type="passwordFieldTypenew" name="newpass" id="newpass" v-model="form.newpass">
                                    <img class="showhide-confirm" src="/img/assets_hotdeal_eye.svg" alt="" v-on:click="switchVisibilitynew">
                                </div>
                                <small class="error fp-pink"> {{error}}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row-100 align center justify-center mtop-30">
                        <button class="btn-secondary mright-5">
                            <a href="/personal-info">batal</a>
                        </button>
                        <button class="btn-primary mleft-5">
                            <a v-on:click="change_password">simpan</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CloseModal from '../component/CloseModal.vue'
    import ProfileMenu from '../component/profile/ProfileMenu.vue'
    import ProfilePointInfo from '../../desktop/component/profile/Profile.vue'
    import apiCustomer from '../../../apis/Customer'

    export default {
        name: "ModalPassword.vue",
        data(){
            return {
                page : this.$route.name,
                address : {},
                form : {
                    oldpass : '',
                    newpass :'',
                },
                hasPassword : false,
                error :'',
                passwordFieldType:"password",
                passwordFieldTypenew:"password",
                passwordFieldTypeConfirmnew:"password"
            }
        },
        mounted (){
            this.PasswordChecker()
        },
        methods:{
            PasswordChecker(){
                apiCustomer.check_password(this.form).then(response => {
                    this.hasPassword = response.data.data.password;
                })
            }, 
            change_password(){
                $("#modal_load").fadeIn();
                apiCustomer.change_password(this.form).then(response => {
                    if(response.data.code == 200){
                        this.profile = response.data.data
                        this.$router.push({path :'/personal-info'})
                    }else{
                        this.error = response.data.message;
                    }
                    $("#modal_load").fadeOut();
                })
            },
            switchVisibility(){
                this.passwordFieldType =  this.passwordFieldType === "password" ? "text" : "password";
            },
            switchVisibilitynew(){
                this.passwordFieldTypenew =  this.passwordFieldTypenew === "password" ? "text" : "password";
            },
            switchVisibilityConfirmnew(){
                this.passwordFieldTypeConfirmnew =  this.passwordFieldTypeConfirmnew === "password" ? "text" : "password";
            }
        },
        components : {
            CloseModal,
            ProfileMenu,
            ProfilePointInfo
        }
    }
</script>

<style>

</style>