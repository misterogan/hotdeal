<template>
    <div>
        <div id="wishlist_detail_modal" class="modal">
            <div class="modal-dialog w-500">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <VueSlickCarousel class="slider-modal" :arrows="true" :dots="true">
                        <div>
                            <router-link to="/promo-list">
                                 <img src="img/380x352.svg" alt="">
                            </router-link>           
                        </div>
                        <div>
                            <router-link to="/promo-list">
                                 <img src="img/380x352.svg" alt="">
                            </router-link>           
                        </div>
                        <div>
                            <router-link to="/promo-list">
                                 <img src="img/380x352.svg" alt="">
                            </router-link>           
                        </div>
                        <div>
                            <router-link to="/promo-list">
                                 <img src="img/380x352.svg" alt="">
                            </router-link>           
                        </div>
                        <div>
                            <router-link to="/promo-list">
                                 <img src="img/380x352.svg" alt="">
                            </router-link>           
                        </div>
                    </VueSlickCarousel>
                    <div class="content-modal">
                        <h5>Weekender Bag Coach</h5>
                        <div class="long-desc">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                                Cras ut pretium metus. Proin in semper neque. 
                                Donec eu purus non quam sollicitudin hendrerit ac egestas erat. Aliquam erat volutpat. 
                                Praesent venenatis, urna a fringilla pharetra, massa turpis aliquet odio, ut porta odio nisl vitae purus. 
                                Quisque volutpat convallis vehicula. Integer velit quam, lobortis et hendrerit nec, ornare quis libero. 
                                Curabitur malesuada dignissim diam. Donec elementum ultricies nibh eu auctor. 
                                Praesent at lectus a quam congue sagittis facilisis accumsan dui. Morbi bibendum libero quis augue luctus, 
                                ut tincidunt eros malesuada.
                            </p>
                            <p>
                                In suscipit turpis vitae ante aliquam mattis. Praesent dapibus viverra accumsan. 
                                Pellentesque volutpat, erat et eleifend consectetur, arcu tellus laoreet tellus, 
                                nec maximus elit nisi ut neque. Fusce tristique gravida dignissim. 
                                Duis efficitur feugiat risus eu sagittis. Vivamus suscipit, nunc at gravida fermentum, 
                                elit elit vestibulum quam, eu porttitor quam ipsum vel est. Ut ac tempor erat, 
                                sed rhoncus dolor. Integer hendrerit aliquam lectus, id lacinia turpis ultrices at. 
                                Pellentesque tristique eu arcu vitae mattis. Vivamus a consectetur nibh. 
                                Morbi vulputate congue molestie. Duis neque leo, rhoncus id maximus quis, bibendum sit amet augue.
                            </p>
                        </div>
                        <div class="modal-desc">
                            <div class="col-2">
                                <div class="desc-product">
                                    <ul class="rating">
                                        <li><img src="img/full_icon_love.svg" alt=""></li>
                                        <li><img src="img/full_icon_love.svg" alt=""></li>
                                        <li><img src="img/full_icon_love.svg" alt=""></li>
                                        <li><img src="img/ic_love_blank.svg" alt=""></li>
                                        <li><img src="img/ic_love_blank.svg" alt=""></li>
                                        <!-- <li>3.0</li>
                                        <li>(120)</li> -->
                                    </ul>
                                    <h5 class="product-name">Pink Bubble Gum</h5>
                                    <div class="price">
                                        <h5>Rp 2.320.000</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="detail-seller">
                                    <img class="img-seller" src="img/pic_user.svg" alt="">
                                    <div class="col">
                                        <h6 class="name-seller">Penjual yang Baik</h6>
                                        <h6 class="address-seller">Depok, Jawa Barat</h6>
                                        <div class="see-all-product">
                                            <p><a href="product-list.html">Lihat Semua Produk</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="change_address_modal" class="modal">
            <div class="modal-dialog">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <h4>Ubah Alamat Pengiriman</h4>
                        <div class="mbottom-25">
                            <div class="row-profile justify-between">
                                <div class="col">
                                    <h5 class="black">Alamat Utama</h5>
                                </div>
                                <div class="sub-menu">
                                    <div class="col">
                                        <p class="purple">Save</p>
                                    </div>
                                    <div class="col">
                                        <p class="purple">Gunakan Sebagai Alamat Utama</p>
                                    </div>
                                    <div class="col">
                                        <p class="purple">Tambah Alamat +</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row-profile">
                                <h6 class="grey-600">Muklis Hilman</h6>
                            </div>
                            <div class="row-profile">
                                <h6 class="grey w-55">Jl. Menteng Pulo, RT.3/RW.14, Menteng Dalam
                                    Kec. Tebet, Kota Jakarta Selatan,
                                    Daerah Khusus Ibukota Jakarta 12870
                                </h6>
                            </div>
                        </div>
                        <div class="row-edit-profile">
                            <h5 class="black">Nama Penerima</h5>
                            <input type="text" name="" id="">
                            <h5 class="black">Nomor Telepon</h5>
                            <input type="number" name="" id="">
                            <h5 class="black">Alamat</h5>
                            <input class="address" type="text" name="" id="">
                            <h5 class="black">Kota</h5>
                            <input type="text" name="" id="">
                            <h5 class="black">Kode Pos</h5>
                            <input type="number" name="" id="">
                            <h5 class="black">Label Alamat</h5>
                            <input type="text" name="" id="">
                            <hr>
                        </div>
                        <div class="mbottom-25">
                            <div class="row-profile justify-between">
                                <div class="col">
                                    <h5>Alamat Tambahan</h5>
                                </div>
                                <div class="sub-menu">
                                    <div class="col">
                                        <p class="purple">Ubah</p>
                                    </div>
                                    <div class="col">
                                        <p class="purple">Gunakan Sebagai Alamat Utama</p>
                                    </div>
                                    <div class="col">
                                        <p class="purple">Tambah Alamat +</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row-profile">
                                <h6 class="grey-600">Muklis Hilman</h6>
                            </div>
                            <div class="row-profile">
                                <h6 class="grey w-55">Jl. Menteng Pulo, RT.3/RW.14, Menteng Dalam
                                    Kec. Tebet, Kota Jakarta Selatan,
                                    Daerah Khusus Ibukota Jakarta 12870
                                </h6>
                            </div>
                            <div class="row-profile">
                                <div class="d-flex align-center">
                                    <img class="mright-20" src="img/ic_trash.svg" alt="">
                                    <small class="purple">Untuk penambahan alamat user, maksimal 5 (lima) alamat berbeda </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="review_thanks_modal" class="modal">
            <div class="modal-dialog">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="form-payment">
                            <h2>Terimakasih telah memberi ulasan</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="refund_modal" class="modal">
            <div class="modal-dialog">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="form-payment">
                            <h2>Modal Refund</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="notif_detail_wait" class="modal">
            <div class="modal-dialog">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="open-notif">
                            <div class="list-notif">
                                <div class="col-5p">
                                    <div class="dot-red"></div>
                                </div>
                                <div class="col-95p">
                                    <div class="row-100">
                                        <div class="title">
                                            <h5>Pesanan Diterima!</h5>
                                            <h5 class="date">21 / 02 / 2021</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="container-img">
                                                <img src="img/50x50.svg" alt="">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="container-img">
                                                <img src="img/50x50.svg" alt="">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="container-img">
                                                <img src="img/50x50.svg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-text">
                                <div class="col text">
                                    <h6>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras ut pretium metus. Proin in semper neque. Donec eu purus non quam sollicitudin hendrerit ac egestas erat. Aliquam erat volutpat.
                                    </h6>
                                </div>
                                <div class="col seller">
                                    <div class="detail-seller">
                                        <div class="col-img">
                                            <img class="img-seller" src="img/profile.svg" alt="">
                                        </div>
                                        <div class="col">
                                            <h6 class="name-seller">Penjual Yang Baik</h6>
                                            <h6 class="address-seller">Depok, Jawa Barat</h6>
                                            <div class="see-all-product">
                                                <p>Lihat Semua Produk</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <img src="img/ic_proses2.svg" alt="">
                            </div>
                            <div class="row">
                                <div class="form-payment">
                                    <h4>Menunggu Pembayaran</h4>
                                    <h6 class="black">Pemesanan Berhasil Dengan nomor Pesanan:</h6>
                                    <h6 class="black">80777081289838209</h6>
                                    <h6 class="black">Terima kasih telah berbelanja di Hotdeal</h6>
                                    <hr>
                                    <div class="payment-before">
                                        <h5>Lakukan Pembayaran Sebelum :</h5>
                                        <h5>10 / 04 / 2021</h5>
                                        <h5>21:00:00</h5>
                                    </div>
                                    <div class="copy-rek">
                                        <h6 class="trf">Transfer Melalui ATM BCA ke no Rek 1600 4000 300 200 a.n PT Hot Deal Indonesia</h6>
                                        <h6 class="purple">Salin</h6>
                                    </div>
                                    <h6 class="purple">Lihat Cara Pembayaran</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="notif_detail_sent" class="modal">
            <div class="modal-dialog">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="open-notif">
                            <div class="list-notif">
                                <div class="col-5p">
                                    <div class="dot-red"></div>
                                </div>
                                <div class="col-95p">
                                    <div class="row-100">
                                        <div class="title">
                                            <h5>Pesanan Diterima!</h5>
                                            <h5 class="date">21 / 02 / 2021</h5>
                                        </div>
                                    </div>
                                    <div class="row-100">
                                        <div class="col-3">
                                            <div class="container-img">
                                                <img src="img/50x50.svg" alt="">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="container-img">
                                                <img src="img/50x50.svg" alt="">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="container-img">
                                                <img src="img/50x50.svg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-text">
                                <div class="col text">
                                    <h6>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras ut pretium metus. Proin in semper neque. Donec eu purus non quam sollicitudin hendrerit ac egestas erat. Aliquam erat volutpat.
                                    </h6>
                                </div>
                                <div class="col seller">
                                    <div class="detail-seller">
                                        <div class="col-img">
                                            <img class="img-seller" src="img/pic_user.svg" alt="">
                                        </div>
                                        <div class="col">
                                            <h6 class="name-seller">Penjual Yang Baik</h6>
                                            <h6 class="address-seller">Depok, Jawa Barat</h6>
                                            <div class="see-all-product">
                                                <p>Lihat Semua Produk</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-90 m-auto">
                                <img src="img/ic_proses3.svg" alt="">
                                <img src="img/ic_checklist.svg" alt="">
                            </div>
                            <div class="row">
                                <div class="form-payment">
                                    <h4>Barang Dikirim</h4>
                                    <h6 class="black">Pesanan Berhasil Dikirim Dengan nomor Resi:</h6>
                                    <div class="d-flex">
                                        <h6 class="black mright-20">80777081289838209</h6>
                                        <h6 class="purple">Lacak</h6>
                                    </div>
                                    <h6 class="black">Terima kasih telah berbelanja di Hotdeal</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="notif_detail_acc" class="modal">
            <div class="modal-dialog">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="open-notif">
                            <div class="list-notif">
                                <div class="col-5p">
                                    <div class="dot-red"></div>
                                </div>
                                <div class="col-95p">
                                    <div class="row-100">
                                        <div class="title">
                                            <h5>Pesanan Diterima!</h5>
                                            <h5 class="date">21 / 02 / 2021</h5>
                                        </div>
                                    </div>
                                    <div class="row-100">
                                        <div class="col-3">
                                            <div class="container-img">
                                                <img src="img/50x50.svg" alt="">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="container-img">
                                                <img src="img/50x50.svg" alt="">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="container-img">
                                                <img src="img/50x50.svg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-text">
                                <div class="col text">
                                    <h6>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras ut pretium metus. Proin in semper neque. Donec eu purus non quam sollicitudin hendrerit ac egestas erat. Aliquam erat volutpat.
                                    </h6>
                                </div>
                                <div class="col seller">
                                    <div class="detail-seller">
                                        <div class="col-img">
                                            <img class="img-seller" src="img/pic_user.svg" alt="">
                                        </div>
                                        <div class="col">
                                            <h6 class="name-seller">Penjual Yang Baik</h6>
                                            <h6 class="address-seller">Depok, Jawa Barat</h6>
                                            <div class="see-all-product">
                                                <p>Lihat Semua Produk</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-90 m-auto">
                                <img src="img/ic_proses4.svg" alt="">
                                <img src="img/ic_checklist.svg" alt="">
                            </div>
                            <div class="row">
                                <div class="form-payment">
                                    <h4>Barang Dikirim</h4>
                                    <h6 class="black">Pesanan Berhasil Dikirim Dengan nomor Resi:</h6>
                                    <div class="d-flex">
                                        <h6 class="black mright-20">80777081289838209</h6>
                                        <h6 class="purple">Lacak</h6>
                                    </div>
                                    <h6 class="black">Terima kasih telah berbelanja di Hotdeal</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueSlickCarousel from 'vue-slick-carousel'
    import 'vue-slick-carousel/dist/vue-slick-carousel.css'
    import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
    export default {
        name: "Modal.vue",
        data: () => ({
            displayDays: 0,
            displayHours: 0,
            displayMinutes: 0,
            displaySeconds: 0
        }),
        computed: {
            _seconds: () => 1000,
            _minutes() {
                return this._seconds * 60;
            },
            _hours(){
                return this._minutes * 60;
            },
            _days(){
                return this._hours * 24;
            }
        },
        methods: {
            formatNum : (num) => num < 10 ? '0' + num : num,
            showRemaining(){
                const timer = setInterval(()=> {
                    const now = new Date();
                    const end = new Date(2022, 8, 27, 21, 40, 10, 10);
                    const distance = end.getTime() - now.getTime();

                    if(distance < 0){
                        clearInterval(timer);
                        this.expired = true;
                        return
                    }
                    const days = Math.floor((distance / this._days));
                    const hours = Math.floor((distance % this._days) / this._hours);
                    const minutes = Math.floor((distance % this._hours) / this._minutes);
                    const seconds = Math.floor((distance % this._minutes) / this._seconds);
                    this.displayMinutes = this.formatNum(minutes);
                    this.displaySeconds = this.formatNum(seconds);
                    this.displayHours = this.formatNum(hours);
                    this.displayDays = this.formatNum(days);
                    this.loaded = true;
                }, 1000);
            },
            show_active_voucher(){
                $("#voucher_active").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
                $("#voucher_card_modal").fadeOut(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_voucher(){
                $("#voucher_card_modal").fadeIn(function () {
                    $("#voucher_card_modal").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            show_detail(){
                $("#promo_detail_modal").fadeIn(function () {
                    $("#promo_detail_modal").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            show_input_otp(){
                $("#input_otp_checkout").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
                $("#otp_warning_modal").fadeOut(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_success_otp(){
                $("#otp_success").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
                $("#input_otp_checkout").fadeOut(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_input_otp(){
                $("#input_otp").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
                $("#sending_otp").fadeOut(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            closedeleteall(){
                $('#confirmation_delete_modal').fadeOut()
            }
        },
        components:{
            VueSlickCarousel
        }
    }
</script>

<style>

</style>