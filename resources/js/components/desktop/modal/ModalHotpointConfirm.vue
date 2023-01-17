<template>
    <div id="confirm_pin_point" class="modal">
        <div class="modal-dialog w-350">
            <div class="modal-body">
                <span class="close-modal" @click="changeStatus(false , true)"></span>
                <div class="content-modal">
                    <h4>PIN Hotpoint</h4>
                    <div class="otp w-100">
                        <div class="input-otp">
                            <!-- <input type="password" v-model="pin" placeholder="Masukaan PIN"> -->
                            <div class="form-input">
                                <v-otp-input
                                    autocomplete="off"
                                    ref="otpInput"
                                    input-classes="pin-input"
                                    separator=" "
                                    value=""
                                    :num-inputs="6"
                                    :should-auto-focus="true"
                                    :is-input-num="true"
                                    @on-complete="handleOnComplete($event)"
                                    input-type="password"
                                    input-mode="numeric"
                                />
                                <img src="/img/assets_hotdeal_eye.svg" alt="" class="show-password pointer">
                            </div>
                        </div>
                        <small class="error">{{error_message}}</small>
                        <div class="btn-option">
                            <button class="cancel btn-link" @click="changeStatus(false , true)">
                                nanti saja
                            </button>
                            <button class="btn-primary agree" href="javascript:void(0)" char="show_success_otp" @click="validatePin">
                                OK
                            </button>
                        </div>
                        <button class="cancel btn-link mtop-20" @click="confirmPin">
                            Lupa pin
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import CloseModal from '../component/CloseModal.vue'
    import apiCustomer from '../../../apis/Customer'
    import ModalForgetPinConfirmation from './ModalForgetPinConfirmation.vue'
    export default {
        data(){
            return {
                btn_request : true,
                pin : null,
                error_message : ''
            }
        },
        name : "ModalHotpointConfirm",
        mounted(){
        },
        methods: {
            handleOnComplete(value){
                this.pin = value
            },
            confirmPin(){ 
               $("#forget_pin_modal_confirmation").fadeIn();
               $("#confirm_pin_point").fadeOut();
            },
            validatePin(){
                if(this.btn_request){
                    this.btn_request = false
                    apiCustomer.validatePin({pin : this.pin}).then( response => {

                        if(response.data.data.status == true){
                            this.status = true
                            return this.changeStatus(true)
                        }else{
                            this.error_message = response.data.data.message
                            this.btn_request = true
                        }
                    });
                }
            },
            changeStatus(status){
                this.status = status;
                this.$emit('validated', {status : status})
            }
        },
        components:{
                CloseModal,
                ModalForgetPinConfirmation
        }
    }
</script>