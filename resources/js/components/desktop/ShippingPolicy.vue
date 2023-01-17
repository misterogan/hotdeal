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
                            <li><router-link to="/policies/refund-policy">Kebijakan refund</router-link></li>
                            <li class="active"><router-link to="/policies/shipping-policy">Ketentuan pengiriman</router-link></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-55p">
                <div class="row-100">
                    <div class="box-100p">
                        <div class="policy-title">
                            <h1>{{title}}</h1>
                        </div>
                        <!-- <div class="policy-body">
                            <h3>ketentuan pengiriman</h3>
                            <ol>
                                <li>Perusahaan bekerja sama dengan pihak ketiga dalam jasa pengiriman yang ditunjuk oleh Perusahaan.</li>
                                <li>Perusahaan memiliki wewenang penuh untuk hal-hal yang disebutkan di bawah ini dan Pengguna tunduk pada ketentuan yang ditentukan oleh Perusahaan:
                                    <ul>
                                        <li>Menentukan jam kerja operasional tim logistik Perusahaan;</li>
                                        <li>Menunjuk jasa pengiriman yang akan digunakan untuk setiap Pengguna, produk atau wilayah pengiriman;</li>
                                        <li>Mengatur jadwal pengiriman pesanan sesuai dengan kepentingan perusahaan.</li>
                                    </ul>
                                </li>
                                <li>Dengan mempertimbangkan kemampuan perusahaan jasa pengiriman, Perusahaan memiliki wewenang untuk menentukan periode pengiriman barang ke setiap wilayah pengiriman yang dituju sebagaimana yang ditampilkan di Platform.</li>
                                <li>Periode pengiriman barang yang tercantum di Platform adalah perkiraan dan Perusahaan berhak untuk merubahnya setiap saat tanpa pemberitahuan sebelumnya.</li>
                                <li>Perusahaan tidak menjamin bahwa setiap periode pengiriman barang tidak akan mengalami keterlambatan dengan alasan apapun, dan Perusahaan tidak bertanggung jawab jika Pengguna menderita kerugian akibat dari keterlambatan tersebut.</li>
                                <li>Perkiraan masa pengiriman yang tertera di Platform terhitung sejak barang siap dikirim oleh tim logistik, dan periode tersebut tidak termasuk periode pengadaan barang.</li>
                                <li>Pengiriman untuk jenis barang tertentu, seperti cairan, gas dan bahan mudah terbakar atau jenis barang lainnya yang dapat ditentukan kemudian, dapat memakan waktu lebih lama dari yang telah disampaikan di Platform karena perbedaan penanganan pengiriman.</li>
                                <li>Pengiriman ke beberapa wilayah yang sulit dijangkau seperti pulau-pulau terisolasi atau tempat-tempat terpencil, atau alasan lain yang mungkin memakan waktu lebih lama dari yang telah disampaikan di Platform atau bahkan penolakan proses pengiriman ke wilayah tersebut.</li>
                            </ol>
                        </div> -->
                        <div class="policy-body" v-html="description">
                        
                        </div>
                    </div>
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
            this.get_shipping_policy()
        },
        methods: {
            get_shipping_policy() {
                Customer.shipping_policy().then(response => {
                    this.title = response.data.data.title
                    this.description = response.data.data.description
                })
            },
        },
    }
</script>

<style scoped>

</style>