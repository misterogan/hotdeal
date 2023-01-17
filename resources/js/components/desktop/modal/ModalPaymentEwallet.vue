<template>
    <div id="ewallet_payment_modal" class="modal">
            <div class="modal-dialog w-350">
                <div class="modal-body">
                    <span class="close-modal" @click="closeModals"></span>
                    <div class="content-modal">
                        <div class="payment-ewallet">
                            <!-- <h6 class="title">pembayaran</h6>
                            <h5>Referensi ID : 001</h5>
                            <h5>pembayaran sebelum :</h5>
                            <h5 style="color:#FF6A00;">31 Januari 2022 11:00 WIB</h5>
                            <hr> -->
                            <h5>Total pembayaran {{ total_payment | RupiahFormat}}</h5>
                            <h5>pembayaran dengan</h5>
                            <div class="total-pay">
                                <div class="logo">
                                    <img src="/img/logo_ovo.svg" alt="">
                                </div>
                                <div class="rupiah">
                                 {{ total_payment | RupiahFormat}}
                                </div>
                            </div>
                            <h5>Nomor Hp</h5>
                            <input type="number" name="phone" v-model="phone">
                            <div><span style="color:red;">{{error_validation}}</span></div>
                            <br>
                            <div class="disclaimer">
                                <h5>Jika transaksimu tidak berhasil</h5>
                                <ol>
                                    <li>Masukan nomor telepon dan coba kembali.</li>
                                    <li>Hapus cache aplikasi ovo di handphone kamu.</li>
                                    <li>Jika kamu masih mengalami kendala yang sama, coba gunakan metode pembayaran yang lain.</li>
                                </ol>
                                <p>Kamu tidak akan dikenakan biaya dua kali ketika mencoba kembali.</p>
                            </div>
                            <button class="btn-cta" @click="createOrderOvo">bayar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    // import CloseModal from '../component/CloseModal.vue'

    export default {
        name: "ModalPaymentEwallet.vue",
        props: ['total_payment'],
        data(){
            return {
                phone : '',
                error_validation : ''
            }
        },
        methods : {
            createOrderOvo(){
                let phone = this.phone
                if(phone.length < 11){
                    return this.error_validation = "Nomor telepon harus minimal 11 angka."
                }
                this.$emit('updatePhone', {phone : this.phone , alert : true})
            },
            closeModals(){
                this.phone = '';
                this.$emit('updatePhone', {phone : this.phone , alert : false})
            }
        }
    }
</script>

<style>

</style>