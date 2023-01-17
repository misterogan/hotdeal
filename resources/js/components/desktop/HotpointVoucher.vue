<template>
    <div>
        <div class="hotpoint-voucher">
            <div class="row top-banner">
                <img src="/img/Hotdeal-x-Cashtree-Banner-1780x450-.webp" alt="">
            </div>
            <div class="row align-stretch content-voucher">
                <div class="col-4 col-sm-12 mright-5">
                    <div class="square-white-shadow">
                        <h4 class="mbottom-30">Reedeem Voucher</h4>
                        <div class="d-flex align-center justify-between mbottom-30" v-if="is_login">
                            Hot point
                            <div class="d-flex align-center">
                                <img class="mright-10" src="/img/ic_hotpoint.svg" alt="" width="25">
                                <b style="font-size: 20px">{{point | NumberFormat}}</b>
                            </div>
                        </div>
                        <div class="d-flex flex-column mbottom-10">
                            <span class="fp-pink fs-11" v-if="!is_login">Silakan Login Terlebih Dahulu Untuk Redeem Voucher</span>
                        </div>
                        <div class="d-flex flex-column mbottom-20">
                            Kode Voucher
                            <input id="in_out_input" class="input-custom" v-model="form.v_code.value" type="text" placeholder="Masukkan kode voucher">
                        </div>
                        <div class="d-flex flex-column mbottom-10">
                            Email Akun Cashtree
                            <input id="in_out_input2" class="input-custom" v-model="form.v_email.value" type="text"  placeholder="Masukkan email Cashtree yang digunakan saat pembelian">
                        </div>
                        <div class="d-flex flex-column mbottom-10">
                            <span class="error">{{form.message}}</span>
                        </div>
                        <button @click="redeemVoucher" class="btn-primary m-auto btn-check-voucher">cek kode voucher</button>
                    </div>
                </div>
                <div class="col-8 col-sm-12 mleft-5">
                    <div class="square-white-shadow">
                        <h4 class="mbottom-40">Hot point redeem voucher</h4>
                        <div class="d-flex flex-column">
                            Info Hot Point
                            <span class="mtop-5">voucher yang kamu redeem akan masuk ke Hot Point. 1 (satu) Hot Point = Rp 1</span>
                        </div>
                        <div class="mtop-20">
                            <div class="mbottom-15 border-top">cara redeem voucher</div>
                            <div class="d-flex align-center mbottom-15">
                                <img class="mright-15" src="/img/img_hotvoucher.svg" alt="">
                                <div class="d-flex flex-column">
                                    cek aplikasi cashtree
                                    <span>Buka aplikasi Cashtree untuk dapatkan kode voucher dan klik Redeem Sekarang</span>
                                </div>
                            </div>
                            <div class="d-flex align-center mbottom-15">
                                <img class="mright-15" src="/img/img_hotvoucher2.svg" alt="">
                                <div class="d-flex flex-column">
                                    temukan kode voucher
                                    <span>Masukkan kode voucher dan tekan tombol 'Cek Kode Voucher'</span>
                                </div>
                            </div>
                            <div class="d-flex align-center mbottom-15">
                                <img class="mright-15" src="/img/img_hotvoucher3.svg" alt="">
                                <div class="d-flex flex-column">
                                    gunakan Hot Point
                                    <span>Setelah redeem, Hot Point sudah siap digunakan (1 Hot Point = 1 Rupiah) </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal-hotpoint-voucher :hotpoint_data="hotpoint" @action="watchAction($event)" ></modal-hotpoint-voucher>
    </div>
</template>

<script>
    import ModalHotpointVoucher from '../desktop/modal/ModalHotpointVoucher.vue'
    import apiCustomer from '../../apis/Customer'
    import Message from '../../utils/Message'
    import User from '../../apis/User'
    export default {
        data(){
            return {
                hotpoint : {},
                profile : {},
                point : 0,
                btn_redeem : true,
                is_login : User.is_login(),
                form : {
                    v_code : {
                        value : '',
                        message : ''
                    },
                    v_email : {
                        value : '',
                        message : ''
                    },
                    message : ''
                }
            }
        },
        mounted(){
           this.getProfile()
        },

        methods: {
            getProfile(){
                User.profile({}).then( response => {
                    this.profile = response;
                    this.point  = this.profile.data.point
                })
                
            },
            show_modal(){
                $("#modal_hotvoucher").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            redeemVoucher(){
                $("#modal_load").fadeIn();
                 if(this.btn_redeem == true){
                    this.btn_redeem = false;
                    apiCustomer.voucherRedeem({code:this.form.v_code.value , email :this.form.v_email.value}).then( response => {
                        $("#modal_load").fadeOut();
                        if(response.data.code != 200){
                            this.form.message = response.data.message
                        }else{
                            this.hotpoint = response.data.data
                            this.show_modal();
                        }
                        this.btn_redeem = true;
                    });
                 }
            },
            watchAction(event){
                if(event == 'cancel'){
                    $("#modal_hotvoucher").fadeOut()
                }else{
                    apiCustomer.voucherClaim({code:this.form.v_code.value , email :this.form.v_email.value}).then( response => { 
                        $("#modal_load").fadeOut();
                        $("#modal_hotvoucher").fadeOut()
                        Message.alert(response.data.message , 'Informasi' , 3000)
                    });
                }
            }
        },
        components:{
            ModalHotpointVoucher
        }
       
    }
</script>

<style scoped>

</style>
