<template>
    <div class="flex-basis-60">
        <div class="box3-shadow-white">
            <h2 class="mbottom-25">Keamanan</h2>
            <div class="row-100 mbottom-15">
                <div class="col-3">
                    <h5 class="fw-600">Kata Sandi</h5>
                </div>
                <div class="col-9">
                    <h5 class="fw-600 fp-purple pointer" @click="show_modal_password">Atur Ulang</h5>
                </div>
            </div>
            <div class="row-100">
                <div class="col-3">
                    <h5 class="fw-600">PIN Hot Point</h5>
                </div>
                <div class="col-9 d-flex">
                    <h5 class="fw-600 fp-purple pointer" v-if="hasPin == 0" @click="show_pin_modal">tambah pin</h5>
                    <h5 class="fw-600 fp-purple pointer mright-10" v-if="hasPin == 1" href="javascript:void(0)" @click="show_pin_update_modal">ubah pin</h5>
                    <h5 class="fw-600 fp-purple pointer" v-if="hasPin == 1" href="javascript:void(0)" @click="show_forget_pin_modal">lupa pin</h5>
                </div>
            </div>
        </div>
        <modal-password></modal-password>
        <modal-hotpoint></modal-hotpoint>
        <modal-forget-pin-confirmation></modal-forget-pin-confirmation>
    </div>
</template>

<script>
    import ProfileMenu from './ProfileMenu.vue'
    import ProfilePointInfo from './Profile.vue'
    import apiCustomer from "../../../../apis/Customer";
    import ModalPassword from '../../modal/ModalPassword.vue'
    import ModalHotpoint from '../../modal/ModalHotpoint.vue'
    import ModalForgetPinConfirmation from '../../modal/ModalForgetPinConfirmation.vue'

    export default {
        name: "PasswordForm.vue",
        data(){
            return {
                page : this.$route.name,
                address : {},
                hasPin : 2,
                form : {
                    oldpass : '',
                    newpass :''
                },
                hasPassword : true,
                error :'',
            }
        },
        mounted (){
            this.PasswordChecker()
        },
        methods:{
            PasswordChecker(){
                apiCustomer.check_password(this.form).then(response => {
                    this.hasPassword = response.data.data.password;
                    this.hasPin = response.data.data.pin
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
            show_modal(){
                $("#modal_hotvoucher").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_password(){
                $("#password_modal").fadeIn(function(){
                    $("body").addClass('overflow-hidden');
                });
            },
            show_pin_modal(){
                $("#create_pin_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_pin_update_modal(){
                $("#change_pin_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_forget_pin_modal(){
                $("#forget_pin_modal_confirmation").fadeIn();
            }
        },
        components : {
            ProfileMenu,
            ProfilePointInfo,
            ModalPassword,
            ModalHotpoint,
            ModalForgetPinConfirmation
        }
    }
</script>
<style scoped>

</style>
