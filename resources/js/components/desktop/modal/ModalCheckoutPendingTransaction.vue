<template>
     <div id="waiting_payment_modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-body">
                <span class="close-modal"></span>
                <div class="content-modal">
                    <div class="form-payment" v-if="Object.keys(data).length > 0">
                        <h2>Menunggu Pembayaran</h2>
                        <h5>Pemesanan Berhasil Dengan nomor Pesanan:</h5>
                        <h5>{{data.transaction_number}}</h5>
                        <h5>Terima kasih telah berbelanja di Hotdeal</h5>
                        <hr>
                        <!-- <h5 class="notes">Setiap pembelianmu di Hotdeal 10% nya akan di donasikan ke Dompet Dhuafa</h5> -->
                        <div class="payment-before">
                            <h5>Lakukan Pembayaran Sebelum :</h5>
                            <h5>{{data.expiration_date}}</h5>
                        </div>
                        <div class="copy-rek">
                            <h6 class="trf">Transfer Melalui {{data.bank_code}} VIRTUAL ACCOUNT ke no {{data.account_number}}</h6>
                            <input class="copy-value d-none" v-on:focus="$event.target.select()" ref="clone" readonly :value="data.account_number"/>
                            <h6 class="purple copy pointer" @click="copy">salin</h6>
                            <h6 class="purple copied hide">berhasil disalin</h6>
                        </div>
                        <hr v-if="reload != 'false'">
                        <div class="text-transaksi" v-if="reload != 'false'">
                            <h5>Daftar transaksi dapat di lihat di halaman Profile</h5>
                            <h6><router-link to="/transactions/pending-transaction">Lihat Di sini</router-link></h6>
                        </div>
                        <div class="col">
                            <button class="back mobile">
                                <router-link class="btn-link" to="/">
                                    Kembali Belanja
                                </router-link>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CloseModal from '../component/CloseModal.vue'
    export default {
        name: "ModalCheckoutPendingTransaction.vue",
        props : ['data','reload'],
        mounted(){
        },
        methods : {
            redirect(){
                if(this.reload == 'true'){
                    window.location.href = '/transactions/pending-transaction'
                }   
            },
            copy() {
                $('.copy-value').show()
                this.$refs.clone.focus();
                document.execCommand('copy');
                $('.copy-value').hide()
                $('.copy').addClass('hide');
                $('.copied').removeClass('hide');

                setTimeout(
                    function(){
                        $('.copied').addClass('hide');
                        $('.copy').removeClass('hide');
                    },3000
                ); 
            }
        },
        components:{
                CloseModal
        }
    }
</script>

<style>

</style>