<template>
    <div>
        <div class="rejeki-nomplok">
            <div class="mbottom-10" v-if="is_loading">
                <div class="ph-container">
                    <div style="border-radius: 20px" class="ph-row ph-h200 bg-placeholder mtop-10 mbottom-20"></div>
                </div>
            </div>
            <div v-if="!is_loading">
                <div class="banner-nomplok-home">
                    <rejeki-nomplok-banner></rejeki-nomplok-banner>
                </div>
            </div>  
            <div class="row">
                <div class="dropdown-menu">
                    <div class="menu-dropdown">
                        lihat detail
                        <img class="arrow" src="/img/ic_dropdown_white.svg" alt="">
                    </div>  
                    <ul class="sub-menu-mobile">
                        <li>
                            <p>Tentang Rejeki Nomplok</p>
                            <img src="/img/ic_dropdown_white.svg" alt="">
                            <div class="sub-menu">
                                <span style="text-transform: none;">Rejeki Nomplok merupakan sebuah program promosi utama Hotdeal yang memberikan kesempatan bagi pemenang yang beruntung untuk meraih cashback sebesar 100% dari harga produk yang dibeli.</span>
                            </div>
                        </li>
                        <li>
                            <p>Cara Berpartisipasi</p>
                            <img src="/img/ic_dropdown_white.svg" alt="">
                            <div class="sub-menu">
                                <span style="text-transform: none;">
                                    1. Lakukan pembelian produk yang berlabel "Rejeki Nomplok".<br>
                                    2. Daftar produk yang termasuk dalam "Rejeki Nomplok" juga bisa dilihat dengan klik banner "Rejeki Nomplok" pada halaman web Hotdeal.<br>
                                    3. Setiap pembelian 1 (satu) pcs produk berlabel "Rejeki Nomplok", kamu akan mendapatkan 1 (satu) buah kupon berisi nomor undian (0-9).<br>
                                    4. Kupon undian akan diberikan ketika kamu telah menyelesaikan pembayaran pada setiap transaksi dan telah mengonfirmasi pesanan diterima.<br>
                                    5. Kupon undian yang didapat bisa dilihat dengan klik "Profil", lalu klik tab "Rejeki Nomplok".<br>
                                    6. Bagi kamu yang beruntung, hadiah akan dikirimkan dalam bentuk <b>Hot Point.</b><br>
                                    7. Periode promo setiap minggunya mulai dari hari Minggu pukul 00:00 WIB sampai dengan hari Sabtu pukul 23:59 WIB.<br>
                                    8. Penetapan pemenang di promo Rejeki Nomplok ini bersifat transparan dengan nomor kupon undian pemenang yang ditentukan berdasarkan digit terakhir angka penutupan IHSG pada setiap hari Senin pukul 15:30 WIB.
                                       <br>
                                       *Jika hari Senin bertepatan dengan hari libur nasional, nomor kupon undian yang menang akan ditentukan berdasarkan digit terakhir angka penutupan IHSG di hari selanjutnya.<br>
                                </span>
                            </div>
                        </li>
                        <li>
                            <p>Cara Menentukan Pemenang</p>
                            <img src="/img/ic_dropdown_white.svg" alt="">
                            <div class="sub-menu">
                                <span style="text-transform: none;">
                                    1. Nomor kupon undian yang telah kamu dapatkan akan dicocokkan dengan digit terakhir dari angka penutupan IHSG (bisa dicek juga via www.idx.co.id) di hari Senin pukul 15:30 WIB.<br>
                                       Contoh: Nomor kupon undian yang didapatkan adalah 4 dan digit terakhir angka penutupan dari IHSG adalah 6,904. Maka, nomor kupon tersebut yang beruntung menjadi pemenang Rejeki Nomplok.<br><br>
                                    2. Penetapan pemenang adalah 10% dari total produk yang terjual selama 1 (satu) periode.<br>
                                       Contoh: Produk yang terjual sebanyak 50 pcs, maka 10% dari total produk yang terjual adalah 5. Jadi, akan ada 5 (lima) pemenang beruntung yang mendapatkan Cashback 100%.<br><br>
                                    3. Pemenang yang beruntung akan mendapatkan Cashback 100% dari nilai produk yang telah dibeli. Nilai cashback akan diberikan dalam bentuk Hot Point yang nantinya dapat digunakan untuk bertransaksi kembali di hotdeal.id.
                                </span>
                            </div>
                        </li>
                        <li>
                            <p>Syarat &amp; Ketentuan</p>
                            <img src="/img/ic_dropdown_white.svg" alt="">
                            <div class="sub-menu">
                                <span style="text-transform: none;">
                                    1. Peserta harus telah melakukan verifikasi email dan nomor handphone yang terdaftar pada akun HOTDEAL.<br>
                                    2. Peserta harus melakukan dan menyelesaikan transaksi pembelian produk di hotdeal.id untuk mendapatkan nomor kupon undian.<br>
                                    3. Kupon undian akan diberikan ketika kamu telah menyelesaikan pembayaran pada setiap transaksi dan telah mengonfirmasi pesanan diterima.<br>
                                    4. Semakin banyak melakukan transaksi, maka kesempatan untuk menang menjadi semakin besar.<br>
                                    5. Periode promo setiap minggunya mulai dari hari Minggu pukul 00:00 WIB sampai dengan hari Sabtu pukul 23:59 WIB.<br>
                                    6. Penetapan pemenang di promo Rejeki Nomplok ini bersifat transparan dengan nomor kupon undian pemenang yang ditentukan berdasarkan digit terakhir angka penutupan IHSG pada setiap hari Senin pukul 15:30 WIB.<br>
                                    * Jika hari Senin bertepatan dengan hari libur nasional, nomor kupon undian yang menang akan ditentukan berdasarkan digit terakhir angka penutupan IHSG di hari selanjutnya.<br>
                                    7. Cashback 100% berupa Hot Point akan dikirimkan ke akun pemenang dalam jangka waktu 2 x 24 jam.<br>
                                    8. Hot Point yang diterima hanya dapat digunakan untuk transaksi pembelian produk di hotdeal.id.<br>
                                    9. Hot Point tidak dapat dicairkan, dipindahtangankan, atau ditransfer kepada pihak lain.<br>
                                    10. Promo bisa digabungkan dengan promo lainnya, seperti penggunaan voucher diskon belanja.<br>
                                    11. HOTDEAL berhak untuk membatalkan kemenangan jika ditemukan adanya kecurangan, sabotase, atau hal-hal lain yang melanggar Syarat &amp; Ketentuan.<br>
                                    12. Peserta dilarang melakukan tindakan apapun yang diduga akan merugikan Perusahaan, seperti membuat akun palsu, menduplikasi akun, melakukan tindakan abusif terhadap platform, dan tindakan lainnya.<br>
                                    13. HOTDEAL dapat mengubah Syarat &amp; Ketentuan termasuk mengubah atau menghentikan promo Rejeki Nomplok.<br>
                                    14. Syarat dan Ketentuan promo ini merupakan bagian yang tidak terpisahkan dan satu kesatuan dengan Syarat dan Ketentuan HOTDEAL, serta dapat berubah sewaktu-waktu untuk disesuaikan dengan kebijakan sehubungan dengan promo ini.<br>
                                    15.Dengan mengikuti promo ini, pengguna dianggap mengerti dan menyetujui semua syarat &amp; ketentuan yang berlaku.<br>
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="box-winner">
                    <div class="running-text">
                        <marquee direction="" onmouseover="this.stop();" onmouseout="this.start();">
                            <div class="text-container">
                                <a data-fancybox-group="gallery" class="fancybox" title="THE ELECTRIC LIGHTING ACT:section 35" v-if="text == null || text == ''">Tidak Ada Pemenang</a>
                                <a data-fancybox-group="gallery" class="fancybox" title="THE ELECTRIC LIGHTING ACT:section 35" v-else>{{ text }}</a>
                                <!-- <a data-fancybox-group="gallery" class="fancybox" title="THE ELECTRIC LIGHTING ACT:section 35">herlandy rifa</a> -->
                            </div>
                        </marquee>
                    </div>
                    <div class="see-all">
                        <router-link to="/period-list">
                            <h6>lihat semua periode pemenang</h6>
                        </router-link>  
                    </div>
                </div>
            </div>
            <div class="row mobile">
                <rejeki-nomplok-list-periode></rejeki-nomplok-list-periode>
            </div>
            <div class="row">
                <div class="title-rejeki">
                    <img src="/img/logo_rejeki_nomplok.svg" alt="Produk rejeki nomplok hotdeal.id">
                    <h3>Produk rejeki nomplok</h3>
                </div>
            </div>
            <rejeki-nomplok-list-product></rejeki-nomplok-list-product>
        </div>
    </div>
</template>

<script>

    import apiCustomer from '../../apis/Customer'
    import RejekiNomplokBanner from './RejekiNomplokBanner.vue'
    import RejekiNomplokListPeriode from './RejekiNomplokListPeriode.vue'
    import RejekiNomplokListProduct from './RejekiNomplokListProduct.vue'
    
    export default {
        name: "RejekiNomplok.vue",
        data(){
            return{
                winners : {},
                week : {},
                text : '',
                runningText : '',
                is_loading: false
            }
        },
        components : {
                RejekiNomplokListPeriode,
                RejekiNomplokListProduct,
                RejekiNomplokBanner
        },
        mounted(){
            this.winner()
            this.running_text()
            this.winner_text()
        },
        methods: {
            winner(){
                apiCustomer.winner({week:this.$route.query.week}).then( response => {
                    this.week = response.data.data.week
                    this.winners = response.data.data.winner
                });
            },

            running_text(){
                apiCustomer.rejekiNomplokRunningText({week:this.$route.query.week}).then( response => {
                    this.text = response.data.data.text
                });
            },
            winner_text(){
                apiCustomer.winnerText({week:this.$route.query.week}).then( response => {
                    this.runningText = response.data.data
                });
            }
        },
    }

</script>