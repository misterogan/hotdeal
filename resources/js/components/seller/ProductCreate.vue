<template>
    <div>
        <div class="main-content">
            <div class="center-menu" style="width:100%">
                <div class="main-menu">
                    <div class="box add-product">
                        <h3>tambah produk</h3>
                        <div class="input-data">
                            <form action="">
                                <h5>nama produk (judul)</h5>
                                <input type="text" name="name" placeholder="Masukkan nama produk" @keyup="charCount()">
                                <small>{{ totalcharacter }} characters</small>
                            </form>
                            <form action="">
                                <h5>jenis kategori</h5>
                                <div class="select-category">
                                    <input type="text" name="name" placeholder="Pilih kategori" autocomplete="off" href="javascript:void(0)" @click="show_modal">
                                    <div class="tag">
                                        <h6>Makanan</h6>
                                        <img src="/img/ic_checklist.svg" alt="">
                                    </div>
                                </div>
                            </form>
                            <form action="">
                                <h5>merk</h5>
                                <input type="text" name="name" placeholder="Masukkan merk produk">
                                <small>0/100</small>
                            </form>
                            <form action="" name="desc">
                                <h5>deskripsi produk</h5>
                                <textarea name="" id="" rows="8" placeholder="Tulis deskripsi produk di sini"></textarea>
                            </form>
                            <form>
                                <h5>tambah foto</h5>
                                <div class="add-media">
                                    <form action="">
                                        <input type="image" class="add-image" src="/img/ic_add.svg" width="48" height="48">
                                        <div class="caption">
                                            <h6>foto utama</h6>
                                        </div>
                                    </form>
                                    <form action="">
                                        <input type="image" class="add-image" src="/img/ic_add.svg">
                                        <div class="caption">
                                            <h6>foto 1</h6>
                                        </div>
                                    </form>
                                    <form action="">
                                        <input type="image" class="add-image" src="/img/ic_add.svg">
                                        <div class="caption">
                                            <h6>foto 2</h6>
                                        </div>
                                    </form>
                                    <form action="">
                                        <input type="image" class="add-image" src="/img/ic_add.svg">
                                        <div class="caption">
                                            <h6>foto 3</h6>
                                        </div>
                                    </form>
                                    <form action="">
                                        <input type="image" class="add-image" src="/img/ic_add.svg">
                                        <div class="caption">
                                            <h6>foto 4</h6>
                                        </div>
                                    </form>
                                    <form action="">
                                        <input type="image" class="add-image" src="/img/ic_add.svg">
                                        <div class="caption">
                                            <h6>foto 5</h6>
                                        </div>
                                    </form>
                                </div>
                            </form>
                            <form>
                                <h5>tambah video</h5>
                                <div class="add-media">
                                    <form action="">
                                        <input type="image" class="add-image" src="/img/ic_add.svg">
                                        <div class="caption">
                                            <h6>video</h6>
                                        </div>
                                    </form>
                                    <ul class="disclaimer">
                                        <li>Ukuran: Maks. 30MB dan resolusi maks. 1.280x1.280px</li>
                                        <li>Durasi: 10-60detik</li>
                                        <li>Format: MP4</li>
                                        <li>Catatan: Kamu dapat menampilkan produk saat video sedang diproses. Video akan muncul setelah berhasil diproses.</li>
                                    </ul>
                                </div>
                            </form>
                            <hr>
                            <div class="input-price">
                                <form action="" name="price">
                                    <h5>harga</h5>
                                    <input type="text">
                                </form>
                                <form action="" name="qty">
                                    <h5>stok</h5>
                                    <button class="minus-btn" type="button" name="button">
                                        <img src="/img/btn_minus.svg" alt="" />
                                    </button>
                                    <input type="text" name="name" class="qty">
                                    <button class="plus-btn" type="button" name="button">
                                        <img src="/img/btn_plus.svg" alt="" />
                                    </button>
                                </form>
                            </div>
                            <form action="">
                                <h5>harga jual</h5>
                                <h5 class="mleft-20">Rp 275.000</h5>
                            </form>
                            <button class="btn-primary">
                                <img src="/img/ic_plus.svg" alt="">
                                tambah variasi
                            </button>
                            <hr>
                            <div class="input-weight">
                                <form action="" name="weight">
                                    <h5>berat</h5>
                                    <input type="text" placeholder="Masukkan berat barang, contoh : 100 gram">
                                    <small>dalam satuan gram</small>
                                </form>
                                <form action="" name="weight">
                                    <h5>dimensi</h5>
                                    <input type="text" placeholder="P X L X T">
                                    <small>p x l x t</small>
                                </form>
                            </div>
                            <h5>jasa kurir pengiriman</h5>
                            <p>Aktifkan min. 1 jasa pengiriman. Produk tidak dapat disimpan jika tidak ada jasa kirim yang aktif.
                            Anda juga dapat klik di sini untuk menambahkan jasa kirim pada produk Anda. </p>
                            <div class="opsi-kurir">
                                <div class="reguler">
                                    <div class="reg-1">
                                        <div class="price">
                                            <div class="input-radio">
                                                <div class="label">
                                                    reguler (cashless)
                                                </div>
                                                <h5>Rp 9.000</h5>
                                                <button class="check">
                                                    <input type="checkbox" id="c" />
                                                    <label for="c"></label>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="estimasi">
                                            <h5>SiCepat Reg (maks. 5000gr)</h5>
                                            <h5>JNE Reg (maks. 5000gr)</h5>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="reg-1">
                                        <div class="price">
                                            <div class="input-radio">
                                                <div class="label">
                                                    same day
                                                </div>
                                                <h5>Rp 9.000</h5>
                                                <button class="check">
                                                    <input type="checkbox" id="c1" />
                                                    <label for="c1"></label>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="estimasi">
                                            <h5>SiCepat Reg (maks. 5000gr)</h5>
                                            <h5>JNE Reg (maks. 5000gr)</h5>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="reg-1">
                                        <div class="price">
                                            <div class="input-radio">
                                                <div class="label">
                                                    instant
                                                </div>
                                                <h5>Rp 9.000</h5>
                                                <button class="check">
                                                    <input type="checkbox" id="c2" />
                                                    <label for="c2"></label>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="estimasi">
                                            <h5>SiCepat Reg (maks. 5000gr)</h5>
                                            <h5>JNE Reg (maks. 5000gr)</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p>Pengaturan jasa kirim hanya akan diterapkan pada produk ini. Ongkir yang dimunculkan adalah tarif dasar yang dapat berubah berdasarkan berat & lokasi pembeli.</p>
                            <hr>
                            <div class="input-sku">
                                <form action="" name="sku">
                                    <h5>sku induk</h5>
                                    <input type="text" placeholder="Masukkan SKU">
                                </form>
                            </div>
                            <div class="col select-option" style="width:20%">
                                <h5>status</h5>
                                <div class="select" tabindex="1">
                                    <input class="selectopt" name="status" type="radio" id="option1" value="active" checked>
                                    <label for="option1" class="option">aktif</label>
                                    <input class="selectopt" name="status" type="radio" id="option2" value="inactive">
                                    <label for="option2" class="option">tidak aktif</label>
                                </div>
                            </div>
                        </div>
                        <div class="button">
                            <button class="btn-primary" @click="$router.push('list')">
                                kembali
                            </button>
                            <button class="btn-primary" @click="createProduct()">
                                simpan & tampilkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal></modal>
    </div>
</template>

<script>
    import Modal from '../desktop/Modal.vue'

    export default {
        name: "ProductCreate.vue",
        methods: {
            show_modal(){
                $("#category_modal").fadeIn(function () {
                    $("#category_modal").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            charCount: function(){
                this.totalcharacter = this.message.length;
            }
        },
        components:{
            Modal
        },
    }
</script>
