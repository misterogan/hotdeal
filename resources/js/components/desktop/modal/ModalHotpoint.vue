<template>
<div>
    <div id="create_pin_modal" class="modal">
        <div class="modal-dialog w-350">
            <div class="modal-body">
                <span class="close-modal"></span>
                <div class="content-modal">
                    <div class="row-100 input-pin">
                        <div class="col-12">
                            <form v-on:submit.prevent="savePin()">
                                <h4>Buat pin</h4>
                                <h5 class="mtop-5">Masukkan pin</h5>
                                <div class="form-input mbottom-10">
                                    <v-otp-input
                                        ref="otpInput"
                                        input-type="password"
                                        input-classes="pin-input"
                                        separator=" "
                                        :num-inputs="1"
                                        style="display:none"
                                        :should-auto-focus="true"
                                        :is-input-num="true"
                                    />
                                    <v-otp-input
                                        ref="otpInput"
                                        input-type="password"
                                        input-classes="pin-input"
                                        separator=""
                                        :num-inputs="6"
                                        :should-auto-focus="true"
                                        :is-input-num="true"
                                        @on-change="handleOnChange"
                                        @on-complete="handleOnComplete($event ,'form_new_pin' , 'pin')"
                                        v-model="form_new_pin.pin"
                                    />
                                    <img src="/img/assets_hotdeal_eye.svg" alt="" class="show-password pointer">
                                </div>
                                <h5 class="mtop-5">Konfirmasi pin</h5>
                                <div class="form-input">
                                    <v-otp-input
                                        ref="otpInput"
                                        input-type="password"
                                        input-classes="pin-input"
                                        separator=" "
                                        :num-inputs="6"
                                        :should-auto-focus="true"
                                        :is-input-num="true"
                                        @on-change="handleOnChange"
                                        @on-complete="handleOnComplete($event ,'form_new_pin' , 'confirm')"
                                        v-model="form_new_pin.confirm"
                                    />
                                    <img src="/img/assets_hotdeal_eye.svg" alt="" class="show-password pointer">
                                </div>
                                <small class="error" v-if="error_status">{{error_message}}</small>
                            </form>
                                <div class="row-100 align-center justify-between mtop-30">
                                <button class="btn-secondary" @click="cancel()">Batal</button>
                                <button class="btn-primary" type="button" @click="savePin()">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="change_pin_modal" class="modal">
        <div class="modal-dialog w-350">
            <div class="modal-body">
                <span class="close-modal"></span>
                <div class="content-modal">
                    <div class="row-100 input-pin">
                        <div class="col-12">
                            <form v-on:submit.prevent="updatePin()">
                                <v-otp-input
                                    ref="otpInput"
                                    input-type="password"
                                    input-classes="pin-input"
                                    separator=" "
                                    :num-inputs="1"
                                    style="display:none"
                                    :should-auto-focus="true"
                                    :is-input-num="true"
                                />
                                <h4>Ubah Pin</h4>
                                <h5 class="mtop-5">Masukkan pin lama</h5>
                                <div class="form-input">
                                    <v-otp-input
                                        input-type="password"
                                        input-classes="pin-input"
                                        separator=" "
                                        :num-inputs="6"
                                        :should-auto-focus="true"
                                        :is-input-num="true"
                                        @on-change="handleOnChange"
                                        @on-complete="handleOnComplete($event ,'form_update_pin' , 'lastpin')"
                                    />
                                    <img src="/img/assets_hotdeal_eye.svg" alt="" class="show-password pointer">
                                </div>
                                <h5 class="mtop-5">Masukkan pin baru</h5>
                                <div class="form-input">
                                    <v-otp-input
                                        input-type="password"
                                        input-classes="pin-input"
                                        separator=""
                                        :num-inputs="6"
                                        :should-auto-focus="true"
                                        :is-input-num="true"
                                        @on-change="handleOnChange"
                                        @on-complete="handleOnComplete($event ,'form_update_pin' , 'pin')"
                                        v-model="form_update_pin.pin"
                                    />
                                    <img src="/img/assets_hotdeal_eye.svg" alt="" class="show-password pointer">
                                </div>
                                <h5 class="mtop-5">Konfirmasi pin baru</h5>
                                <div class="form-input">
                                    <v-otp-input
                                        input-type="password"
                                        input-classes="pin-input"
                                        separator=" "
                                        :num-inputs="6"
                                        :should-auto-focus="true"
                                        :is-input-num="true"
                                        @on-change="handleOnChange"
                                        @on-complete="handleOnComplete($event ,'form_update_pin' , 'confirm')"
                                        v-model="form_update_pin.confirm"
                                    />
                                    <img src="/img/assets_hotdeal_eye.svg" alt="" class="show-password pointer">
                                </div>
                                <small class="error" v-if="error_status">{{error_message}}</small>
                                
                            </form>
                            <div class="row-100 align-center justify-between mtop-30">
                                    <button class="btn-secondary" @click="cancel">Batal</button>
                                    <button class="btn-primary" type="button" @click="updatePin">Simpan</button>                                    
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <modal-alert :message="modal_message"></modal-alert>
</div>
</template>
<script>
    import Vue from 'vue'
    import CloseModal from '../component/CloseModal.vue'
    import apiCustomer from '../../../apis/Customer'
    import OtpInput from "@bachdgvn/vue-otp-input";
    Vue.component("v-otp-input", OtpInput);
    import ModalAlert from './ModalAlert.vue'
    import Message from '../../../utils/Message'
    export default {
        data(){
            return {
                error_status : false,
                error_message : '',
                modal_message : '',
                btn_save : true,
                form_new_pin : {
                    pin : '',
                    confirm : ''
                },
                form_update_pin : {
                    lastpin : '',
                    pin : '' ,
                    confirm : ''
                },
                form_forgot_pin : {
                    pin : '' ,
                    confirm : ''
                }
            }
        },
        methods: {
            savePin(){
                if(this.btn_save){
                    this.error_status = false
                    this.error_message = ''
                    this.btn_save = false
                     $("#modal_load").fadeIn();
                    apiCustomer.savePin(this.form_new_pin).then( response => {
                        if(response.data.code == 200){
                            $("#create_pin_modal").fadeOut()
                            $("#change_pin_modal").fadeOut()
                            this.modal_message = response.data.message
                            $('#check-point').prop('checked', false);
                            $("#alert_modal").fadeIn(function (){
                                $("body").addClass('overflow-hidden')
                            })
                             Message.alert(response.data.message,'Informasi');
                            
                            this.$router.go()
                        }else{
                            this.error_message = response.data.message;
                            this.error_status = true
                            this.btn_save = true
                        }
                        $("#modal_load").fadeOut();
                    });
                }
            },
            cancel(){
                $("#create_pin_modal").fadeOut();
                $("#change_pin_modal").fadeOut();
                $('#check-point').prop('checked', false);
            },
            updatePin(){
                this.error_status = false
                this.error_message = ''
                if(this.btn_save){
                    this.btn_save = false
                    $("#modal_load").fadeIn();
                    apiCustomer.updatePin(this.form_update_pin).then( response => {
                        if(response.data.code == 200){
                            $("#modal_load").fadeOut();
                            $("#create_pin_modal").fadeOut();
                            $("#change_pin_modal").fadeOut();
                            this.modal_message = response.data.message
                            Message.alert(response.data.message,'Informasi');
                            $('.pin-input').val('')
                            $('#create_pin_modal').fadeOut();
                            this.$router.go()
                        }else{
                            this.error_message = response.data.message;
                            // if(Object.keys(response.data.message).length > 10 ){
                            //     this.error_message = response.data.message;
                            // }else{
                            //     this.error_message = response.data.message.confirm[0] + ',' +response.data.message.lastpin[0] +','+response.data.message.pin[0]
                            // }
                            this.error_status = true
                            this.btn_save = true
                        }
                        $("#modal_load").fadeOut();
                    });
                }
                
            },
            handleOnComplete(value ,form,key) {
                if(form == 'form_new_pin'){
                    if(key == 'pin'){
                       this.form_new_pin.pin = value 
                    }
                    if(key == 'confirm'){
                       this.form_new_pin.confirm = value 
                    }
                }
                if(form == 'form_update_pin'){
                    if(key == 'lastpin'){
                       this.form_update_pin.lastpin = value 
                    }
                    if(key == 'pin'){
                       this.form_update_pin.pin = value 
                    }
                    if(key == 'confirm'){
                       this.form_update_pin.confirm = value 
                    }
                }
                if(form == 'forgot_pin'){
                   
                }
            },
            handleOnChange(value) {
            }
        },
        components:{
                CloseModal,
                ModalAlert
        }
    }
</script>