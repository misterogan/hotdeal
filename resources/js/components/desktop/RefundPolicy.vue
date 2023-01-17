<template>
    <div>
        <div class="row col3-flex-cart checkout">
            <div class="col-20p">
                <div class="box-filter-purple">
                    <div class="content">
                        <h4>Pusat Bantuan</h4>
                        <ul>
                            <li><router-link to="/policies/terms-of-service">Aturan & kebijakan</router-link></li>
                            <li><router-link to="/policies/privacy-policy">Kebijakan privasi</router-link></li>
                            <li class="active"><router-link to="/policies/refund-policy">Kebijakan refund</router-link></li>
                            <li><router-link to="/policies/shipping-policy">Ketentuan pengiriman</router-link></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-55p">
                <div class="box-100p">
                    <div class="policy-title">
                        <h1>{{title}}</h1>
                    </div>
                    <!-- <div class="policy-body">
                        <h3>pengembalian barang &amp; dana</h3>
                        <h4>pengembalian barang</h4>
                        <ol>
                            <li>Pengguna berhak untuk mengajukan permintaan pengembalian barang jika ditemukan kondisi produk yang diterima sebagai berikut:
                                <ul>
                                    <li>Cacat produksi;</li>
                                    <li>Tidak berfungsi sebagaimana mestinya (<em>malfunction</em>);</li>
                                    <li>Tidak sesuai dengan informasi yang diberikan di Platform;</li>
                                    <li>Terdapat bagian yang tidak lengkap;</li>
                                    <li>Salah pengiriman barang, baik jenis maupun jumlah.</li>
                                </ul>
                            </li>
                            <li>Dalam hal kebijakan pengembalian barang, Perusahaan menentukan hal-hal berikut yang harus dipenuhi oleh Pengguna untuk dapat memproses pengajuan pengembaliannya:
                                <ul>
                                    <li>Pengguna wajib memberikan informasi yang sebenarnya mengenai kondisi barang yang diterima dan dibuktikan dengan lampiran video unboxing dan foto untuk mendukung pernyataannya;</li>
                                    <li>Masa berlaku pengajuan yang dapat diterima oleh Perusahaan adalah 3 (tiga) hari sejak barang diterima oleh Pengguna;</li>
                                    <li>Pengguna bersedia menunggu proses validasi yang dilakukan oleh Perusahaan;</li>
                                    <li>Pengguna tunduk terhadap hasil keputusan atau solusi yang diberikan oleh Perusahaan atas permintaan pengembalian barang yang diajukan.</li>
                                    <li>Jika pengajuan pengembalian barang disetujui, Pengguna dapat mengemas seluruh barang yang akan dikembalikan menjadi satu kesatuan dan mengirimkannya menuju alamat yang akan disediakan oleh tim terkait.</li>
                                    <li>Setelah Pengguna memasukkan nomor resi pengembalian barang, Perusahaan akan memproses Pengembalian Dana sesuai ketentuan di Pasal 31.</li>
                                </ul>
                            </li>
                        </ol>
                        <h4>pengembalian dana</h4>
                        <ol>
                            <li>Perusahaan akan mengembalikan dana kepada Pengguna jika terjadi pembatalan pesanan, pengembalian barang, kelebihan pembayaran, atau hal-hal lain yang dapat membatalkan proses pembelian dan pembayaran yang tidak merugikan Perusahaan dengan proses pengembalian dana tersebut.</li>
                            <li>Perusahaan menentukan periode pengembalian dana sesuai dengan kemampuan Perusahaan, serta kebijakan setiap bank yang ditunjuk oleh Pengguna untuk menerima pengembalian dana sampai Pengguna dapat menggunakan kembali dana tersebut.</li>
                            <li>Pengembalian dana akan dilakukan melalui proses transfer bank atau pengembalian saldo limit ke kartu kredit Pengguna yang digunakan untuk melakukan pembayaran barang terkait atau Pengguna juga bisa memilih untuk dikembalikan dalam bentuk Point. Ketentuan penggunaan Point ini mengacu pada Aturan dan Kebijakan Pasal 38. </li>
                            <li>Untuk proses pengembalian dana melalui transfer bank, Perusahaan membutuhkan informasi akun bank yang ditunjuk Pengguna yang mencakup:
                                <ul>
                                    <li>Nama Bank;</li>
                                    <li>Kota dan Cabang Bank;</li>
                                    <li>Nomor Rekening;</li>
                                    <li>Nama Pemilik Rekening (harus sesuai dengan nama yang tertera di buku tabungan/rekening koran) dan KTP</li>
                                    <li>Foto KTP pengguna</li>
                                </ul>
                            </li>
                            <li>Perusahaan tidak berkewajiban untuk memeriksa kebenaran rekening yang diajukan oleh Pengguna untuk proses pengembalian dana, dan informasi rekening tersebut akan dianggap benar dan disampaikan melalui email ke Perusahaan oleh Pengguna yang berwenang sesuai dengan akun yang terdaftar.</li>
                            <li>Perusahaan tidak bertanggung jawab atas segala konsekuensi hukum dan kerugian yang timbul dari penyampaian informasi pelanggan dan/ atau penyalahgunaan nomor dan nama pemilik rekening untuk proses pengembalian dana.</li>
                            <li>Perusahaan berhak meminta bukti transfer transaksi pembayaran yang dilakukan Pengguna untuk kebutuhan internal Perusahaan, baik sebelum dan sesudah Perusahaan melakukan proses pengembalian dana.</li>
                            <li>Perusahaan akan mengirimkan informasi melalui email bahwa proses pengembalian dana telah selesai dilakukan oleh Perusahaan.</li>
                        </ol>
                    </div> -->
                    <div class="policy-body" v-html="description"></div>
                </div>
            </div>
            <div class="col-25p">
                <div class="box-pink">
                    <div class="content">
                        <h4>perlu bantuan lain</h4>
                        <h4>hubungi customer</h4>
                        <h4 class="mbottom-30">service kami</h4>
                        <h5>email</h5>
                        <h5 class="lowercase">info@hotdeal.id</h5>
                    </div>
                </div>
            </div>
        </div>
        <modal></modal>
    </div>
    
</template>

<script>
    import Customer from '../../apis/Customer'

    export default {
        data() {
            return {
                title: '',
                description: '',
            }
        },
        name: "RefundPolicy.vue",
        mounted() {
            this.get_refund_policy()
        },
        methods: {
            get_refund_policy() {
                Customer.refund_policy().then(response => {
                    this.title = response.data.data.title
                    this.description = response.data.data.description
                })
            },
        },
    }
</script>

<style scoped>

</style>