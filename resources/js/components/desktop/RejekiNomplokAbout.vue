<template>
    <div class="row-hd hd-rejeki-nomplok">
        <div class="col-csp20-2">
            <rejeki-nomplok-nav></rejeki-nomplok-nav>
        </div>
        <div class="col-csm40-10 col-sm-12">
            <rejeki-nomplok-menus></rejeki-nomplok-menus>
            <div class="row-hd rejeki-about desktop">
                <div class="col-6">
                    <div class="point-about">
                        <h5>Tentang Rejeki Nomplok</h5>
                        <p>Rejeki Nomplok merupakan sebuah program promosi utama dan satu-satunya dari Hotdeal yang memberikan kesempatan untuk meraih cashback sebesar 100% dalam bentuk Hot Point dari harga produk yang dibeli. Setiap pembelian produk berlabel "Rejeki Nomplok", kamu akan mendapatkan kupon undian yang akan diundi secara transparan berdasarkan digit terakhir angka penutupan IHSG setiap hari Senin. Nikmati belanja gratis setiap minggu buat kamu pemenang yang beruntung di program Rejeki Nomplok Hotdeal.</p>
                    </div>
                    <div class="point-about">
                        <h5>Cara Ikutan Rejeki Nomplok</h5>
                        <ol>
                            <li>Lakukan pembelian produk yang berlabel "Rejeki Nomplok".</li>
                            <li>Daftar produk yang termasuk dalam "Rejeki Nomplok" juga bisa dilihat dengan klik banner "Rejeki Nomplok" pada halaman web Hotdeal.</li>
                            <li>Setiap pembelian 1 (satu) pcs produk berlabel "Rejeki Nomplok", kamu akan mendapatkan 1 (satu) buah kupon berisi nomor undian (0-9).</li>
                            <li>Kupon undian akan diberikan ketika kamu telah menyelesaikan pembayaran pada setiap transaksi dan telah mengonfirmasi pesanan diterima.</li>
                            <li>Kupon undian yang didapat bisa dilihat dengan klik "Profile", lalu klik tab "Rejeki Nomplok".</li>
                            <li>Bagi kamu yang beruntung, hadiah akan dikirimkan dalam bentuk Hot Point.</li>
                        </ol>
                    </div>
                    <div class="point-about">
                        <h5>Cara Menentukan Pemenang</h5>
                        <ol>
                            <li>Nomor kupon undian yang telah kamu dapatkan akan dicocokkan dengan digit terakhir dari angka penutupan IHSG (bisa dicek juga via www.idx.co.id) di hari Senin pukul 15:15 WIB. 
                                Contoh: Nomor kupon undian yang didapatkan adalah 4 dan digit terakhir angka penutupan dari IHSG adalah 6,904. Maka, nomor kupon tersebut yang beruntung menjadi pemenang Rejeki Nomplok.</li>
                            <li>Contoh: Produk yang terjual sebanyak 50 pcs, maka 10% dari total produk yang terjual adalah 5. Jadi, akan ada 5 (lima) pemenang beruntung yang mendapatkan Cashback 100%.</li>
                            <li>Pemenang yang beruntung akan mendapatkan Cashback 100% dari nilai produk yang telah dibeli. Nilai cashback akan diberikan dalam bentuk Hot Point yang nantinya dapat digunakan untuk bertransaksi kembali di hotdeal.id.</li>
                        </ol>
                    </div>
                </div>
                <div class="col-6">
                    <div class="point-about">
                        <h5>Syarat &amp; Ketentuan</h5>
                        <ol>
                            <li>Peserta harus telah melakukan verifikasi email dan nomor handphone yang terdaftar pada akun HOTDEAL.</li>
                            <li>Peserta harus melakukan dan menyelesaikan transaksi pembelian produk di hotdeal.id untuk mendapatkan nomor kupon undian.</li>
                            <li>Kupon undian akan diberikan ketika kamu telah menyelesaikan pembayaran pada setiap transaksi dan telah mengonfirmasi pesanan diterima.</li>
                            <li>Semakin banyak melakukan transaksi, maka kesempatan untuk menang menjadi semakin besar.</li>
                            <li>Periode promo setiap minggunya mulai dari hari Minggu pukul 00:00 WIB sampai dengan hari Sabtu pukul 23:59 WIB.</li>
                            <li>Penetapan pemenang di promo Rejeki Nomplok ini bersifat transparan dengan nomor kupon undian pemenang yang ditentukan berdasarkan digit terakhir angka penutupan IHSG pada setiap hari Senin pukul 15:15 WIB. * Jika hari Senin bertepatan dengan hari libur nasional, nomor kupon undian yang menang akan ditentukan berdasarkan digit terakhir angka penutupan IHSG di hari selanjutnya.</li>
                            <li>Cashback 100% berupa Hot Point akan dikirimkan ke akun pemenang dalam jangka waktu 2 x 24 jam.</li>
                            <li>Hot Point yang diterima hanya dapat digunakan untuk transaksi pembelian produk di hotdeal.id.</li>
                            <li>Hot Point tidak dapat dicairkan, dipindahtangankan, atau ditransfer kepada pihak lain.</li>
                            <li>Promo bisa digabungkan dengan promo lainnya, seperti penggunaan voucher diskon belanja.</li>
                            <li>HOTDEAL berhak untuk membatalkan kemenangan jika ditemukan adanya kecurangan, sabotase, atau hal-hal lain yang melanggar Syarat &amp; Ketentuan.</li>
                            <li>Peserta dilarang melakukan tindakan apapun yang diduga akan merugikan Perusahaan, seperti membuat akun palsu, menduplikasi akun, melakukan tindakan abusif terhadap platform, dan tindakan lainnya.</li>
                            <li>HOTDEAL dapat mengubah Syarat &amp; Ketentuan termasuk mengubah atau menghentikan promo Rejeki Nomplok.</li>
                            <li>Syarat dan Ketentuan promo ini merupakan bagian yang tidak terpisahkan dan satu kesatuan dengan Syarat dan Ketentuan HOTDEAL, serta dapat berubah sewaktu-waktu untuk disesuaikan dengan kebijakan sehubungan dengan promo ini.</li>
                            <li>Dengan mengikuti promo ini, pengguna dianggap mengerti dan menyetujui semua syarat &amp; ketentuan yang berlaku.</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="rejeki-about mobile">
                <div class="row-hd">
                    <div class="col-6" v-bind:class="menu_active == 'first' ? 'active' : '' " @click="tab('first')">
                        <h5>Deskripsi</h5>
                        <hr>
                    </div>
                    <div class="col-6" v-bind:class="menu_active == 'second' ? 'active' : '' " @click="tab('second')" id="tnc">
                        <h5>Cara Ikutan</h5>
                        <hr>
                    </div>
                </div>
                <div class="tab-content-rejeki">
                    <div class="text-center mtop-15" v-show="menu_active == 'first'">
                        <h5 class="mbottom-10 fw-700">Tentang Rejeki Nomplok</h5>
                        <p>Rejeki Nomplok merupakan sebuah program promosi utama Hotdeal yang memberikan kesempatan bagi pemenang yang beruntung untuk meraih cashback sebesar 100% dari harga produk yang dibeli.</p>
                    </div>
                    <div v-show="menu_active == 'second'">
                        <h5 class="text-center fw-700 mtop-20">Cara Ikutan Rejeki Nomplok</h5>
                        <ol>
                            <li>Lakukan pembelian produk yang berlabel "Rejeki Nomplok".</li>
                            <li>Daftar produk yang termasuk dalam "Rejeki Nomplok" juga bisa dilihat dengan klik banner "Rejeki Nomplok" pada halaman web Hotdeal.</li>
                            <li>Setiap pembelian 1 (satu) pcs produk berlabel "Rejeki Nomplok", kamu akan mendapatkan 1 (satu) buah kupon berisi nomor undian (0-9).</li>
                            <li>Kupon undian akan diberikan ketika kamu telah menyelesaikan pembayaran pada setiap transaksi dan telah mengonfirmasi pesanan diterima.</li>
                            <li>Kupon undian yang didapat bisa dilihat dengan klik "Profile", lalu klik tab "Rejeki Nomplok".</li>
                            <li>Bagi kamu yang beruntung, hadiah akan dikirimkan dalam bentuk Hot Point.</li>
                        </ol>
                        <h5 class="text-center fw-700 mtop-20">Cara Menentukan Pemenang</h5>
                        <ol>
                            <li>Nomor kupon undian yang telah kamu dapatkan akan dicocokkan dengan digit terakhir dari angka penutupan IHSG (bisa dicek juga via www.idx.co.id) di hari Senin pukul 15:15 WIB. 
                                Contoh: Nomor kupon undian yang didapatkan adalah 4 dan digit terakhir angka penutupan dari IHSG adalah 6,904. Maka, nomor kupon tersebut yang beruntung menjadi pemenang Rejeki Nomplok.</li>
                            <li>Contoh: Produk yang terjual sebanyak 50 pcs, maka 10% dari total produk yang terjual adalah 5. Jadi, akan ada 5 (lima) pemenang beruntung yang mendapatkan Cashback 100%.</li>
                            <li>Pemenang yang beruntung akan mendapatkan Cashback 100% dari nilai produk yang telah dibeli. Nilai cashback akan diberikan dalam bentuk Hot Point yang nantinya dapat digunakan untuk bertransaksi kembali di hotdeal.id.</li>
                        </ol>
                        <h5 class="text-center fw-700 mtop-20">Syarat &amp; Ketentuan</h5>
                        <ol>
                            <li>Peserta harus telah melakukan verifikasi email dan nomor handphone yang terdaftar pada akun HOTDEAL.</li>
                            <li>Peserta harus melakukan dan menyelesaikan transaksi pembelian produk di hotdeal.id untuk mendapatkan nomor kupon undian.</li>
                            <li>Kupon undian akan diberikan ketika kamu telah menyelesaikan pembayaran pada setiap transaksi dan telah mengonfirmasi pesanan diterima.</li>
                            <li>Semakin banyak melakukan transaksi, maka kesempatan untuk menang menjadi semakin besar.</li>
                            <li>Periode promo setiap minggunya mulai dari hari Minggu pukul 00:00 WIB sampai dengan hari Sabtu pukul 23:59 WIB.</li>
                            <li>Penetapan pemenang di promo Rejeki Nomplok ini bersifat transparan dengan nomor kupon undian pemenang yang ditentukan berdasarkan digit terakhir angka penutupan IHSG pada setiap hari Senin pukul 15:15 WIB. * Jika hari Senin bertepatan dengan hari libur nasional, nomor kupon undian yang menang akan ditentukan berdasarkan digit terakhir angka penutupan IHSG di hari selanjutnya.</li>
                            <li>Cashback 100% berupa Hot Point akan dikirimkan ke akun pemenang dalam jangka waktu 2 x 24 jam.</li>
                            <li>Hot Point yang diterima hanya dapat digunakan untuk transaksi pembelian produk di hotdeal.id.</li>
                            <li>Hot Point tidak dapat dicairkan, dipindahtangankan, atau ditransfer kepada pihak lain.</li>
                            <li>Promo bisa digabungkan dengan promo lainnya, seperti penggunaan voucher diskon belanja.</li>
                            <li>HOTDEAL berhak untuk membatalkan kemenangan jika ditemukan adanya kecurangan, sabotase, atau hal-hal lain yang melanggar Syarat &amp; Ketentuan.</li>
                            <li>Peserta dilarang melakukan tindakan apapun yang diduga akan merugikan Perusahaan, seperti membuat akun palsu, menduplikasi akun, melakukan tindakan abusif terhadap platform, dan tindakan lainnya.</li>
                            <li>HOTDEAL dapat mengubah Syarat &amp; Ketentuan termasuk mengubah atau menghentikan promo Rejeki Nomplok.</li>
                            <li>Syarat dan Ketentuan promo ini merupakan bagian yang tidak terpisahkan dan satu kesatuan dengan Syarat dan Ketentuan HOTDEAL, serta dapat berubah sewaktu-waktu untuk disesuaikan dengan kebijakan sehubungan dengan promo ini.</li>
                            <li>Dengan mengikuti promo ini, pengguna dianggap mengerti dan menyetujui semua syarat &amp; ketentuan yang berlaku.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import RejekiNomplokBanner from './RejekiNomplokBanner.vue'
    import apiCustomer from '../../apis/Customer'
    import RejekiNomplokNav from '././component/rejekinomplok/RejekiNomplokNav.vue'
    import RejekiNomplokMenus from '././component/rejekinomplok/RejekiNomplokMenus.vue'
   

    export default {
        name: "RejekiNomplokAbout.vue",
        data(){
            return{
                route_active: '',
                text: '',
                periode: '',
                detail: {},
                menu_active : 'first',
                tab_content : '',
            }
        },
        methods: {
            tab(id){
               this.menu_active = id;
            },
            route(route){
                this.route_active = route
            },
            current_week(){
                apiCustomer.currentWeek().then( response => {
                    this.detail = response.data.data
                    if(this.detail.start_month == this.detail.end_month){
                        this.periode = this.detail.start_date + ' - ' + this.detail.end_date + ' ' + this.detail.end_month + ' ' + this.detail.end_year
                    } else {
                        this.periode = this.detail.start_date + ' ' + this.detail.start_month + ' - ' + this.detail.end_date + ' ' + this.detail.end_month + ' ' + this.detail.end_year
                    }
                });
            }
        },
        mounted(){
            this.route_active = this.$route.name;
            this.current_week()
        },
        components : {
            RejekiNomplokBanner,
            RejekiNomplokMenus,
            RejekiNomplokNav
        }
    }
</script>