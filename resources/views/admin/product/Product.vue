<template>
    <div>
        <div class="main-content">
            <div class="center-menu">
                <div class="main-menu">
                    <div class="box add-product">
                        <h3 class="mbottom-30">tambah produk</h3>
                        <form v-on:submit.prevent="">
                            <div class="form-group">
                                <h5>nama produk (judul)</h5>
                                <input type="text" name="name" placeholder="Masukkan nama produk" v-model.trim="$v.form.name.$model" v-on:keyup="countdown" v-model="message" maxlength="100">
                                <div class="validation-error" v-if="!$v.form.name.required && showError == true">Field is required</div>
                                <small class='mright-15 text-right text-small' v-bind:class="{'text-danger': hasError }">{{remainingCount}}</small>
                            </div>
                            <div class="form-group">
                                <h5>jenis kategori</h5>
                                <div class="select-category">
                                    <input type="text" name="name" placeholder="Pilih kategori" autocomplete="off" style="color:transparent" href="javascript:void(0)" @click="show_category_modal">
                                    <div class="tag" v-if="category_name != ''">
                                        <h6>{{category_name}}</h6>
                                        <img src="/img/ic_checklist.svg" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <h5>merk</h5>
                                <input type="text" name="brand" placeholder="Masukkan merk produk" v-model.trim="$v.form.brand.$model" v-on:keyup="countdowns" v-model="messages" maxlength="100">
                                <div class="validation-error" v-if="!$v.form.brand.required && showError == true">Field is required</div>
                                <small class="mright-15">{{remainingCounts}}</small>
                            </div>
                            <div class="form-group">
                                <h5>deskripsi produk</h5>
                                <vue-editor name="description" id="" rows="8" placeholder="Tulis deskripsi produk di sini" v-model.trim="$v.form.description.$model"></vue-editor>
                            </div>
                            <h5>tambah foto <span style="font-size: .8rem;">(max size foto 500KB)</span></h5>
                            <div class="img-variation">
                                <div class="add-media">
                                    <div class="media-upload">
                                        <label for="img_1">
                                            <div class="upload-icon">
                                                <img class="icon preview_img_1" src="/img/ic_add.svg">
                                            </div>
                                        </label>
                                        <input id="img_1" type="file" class="add-image" ref="img_1" @change="imageChange($event , 'img_1' , 'photo')">
                                        <h6 @click="$refs.img_1.click()">foto utama</h6>
                                    </div>
                                </div>
                                <div class="add-media">
                                    <div class="media-upload">
                                        <label for="img_2">
                                            <div class="upload-icon">
                                                <img class="icon preview_img_2" src="/img/ic_add.svg">
                                            </div>
                                        </label>
                                        <input id="img_2" type="file" class="add-image" ref="img_2" @change="imageChange($event , 'img_2' , 'photo')">
                                        <h6 @click="$refs.img_2.click()">foto 1</h6>
                                    </div>
                                </div>
                                <div class="add-media">
                                    <div class="media-upload">
                                        <label for="img_3">
                                            <div class="upload-icon">
                                                <img class="icon preview_img_3" src="/img/ic_add.svg">
                                                <img class="preview">
                                            </div>
                                        </label>
                                        <input id="img_3" type="file" class="add-image" ref="img_3" @change="imageChange($event , 'img_3' , 'photo')">
                                        <h6 @click="$refs.img_3.click()">foto 2</h6>
                                    </div>
                                </div>
                                <div class="add-media">
                                    <div class="media-upload">
                                        <label for="img_4">
                                            <div class="upload-icon">
                                                <img class="icon preview_img_4" src="/img/ic_add.svg">
                                            </div>
                                        </label>
                                        <input id="img_4" type="file" class="add-image" ref="img_4" @change="imageChange($event , 'img_4' , 'photo')">
                                        <h6 @click="$refs.img_4.click()">foto 3</h6>
                                    </div>
                                </div>
                                <div class="add-media">
                                    <div class="media-upload">
                                        <label for="img_5">
                                            <div class="upload-icon">
                                                <img class="icon preview_img_5" src="/img/ic_add.svg">
                                            </div>
                                        </label>
                                        <input id="img_5" type="file" class="add-image" ref="img_5" @change="imageChange($event , 'img_5' , 'photo')">
                                        <h6 @click="$refs.img_5.click()">foto 4</h6>
                                    </div>
                                </div>
                                <div class="add-media">
                                    <div class="media-upload">
                                        <label for="img_6">
                                            <div class="upload-icon">
                                                <img class="icon preview_img_6" src="/img/ic_add.svg">
                                            </div>
                                        </label>
                                        <input id="img_6" type="file" class="add-image" ref="img_6" @change="imageChange($event , 'img_6' , 'photo')">
                                        <h6 @click="$refs.img_6.click()">foto 5</h6>
                                    </div>
                                </div>
                            </div>
                            <h5>tambah video</h5>
                            <div class="img-variation">
                                <div class="add-media">
                                    <div class="media-upload">
                                        <label for="file-input-vide0">
                                            <div class="upload-icon">
                                                <img class="icon" src="/img/ic_add.svg">
                                                <img class="preview">
                                            </div>
                                        </label>
                                        <input id="file-input-vide0" type="file" class="add-image" ref="videoFile" @change="imageChange($event , 'video' , 'video')">
                                        <h6 @click="$refs.videoFile.click()">video</h6>
                                    </div>
                                </div>
                                <div class="keterangan">
                                    <h6>max size video 30 MB</h6>
                                </div>
                            </div>
                            <hr>
                            <div class="input-variation" v-if="variant== false">
                                <div class="add-variation">
                                    <button style="width: 100%" class="btn-secondary" @click="active_variant()">
                                        <img src="/img/ic_plus_vendor.svg" alt="">
                                        Aktifkan variasi
                                    </button>
                                </div>
                            </div>
                            <div v-if="variant == true">
                                <div class="input-variation" v-if="variant_1_status == true">
                                    <h5>Variasi 1</h5>
                                    <div class="add-variation">
                                        <span class="close" @click="reset_variant"></span>
                                        <div class="form-group">
                                            <h5>nama</h5>
                                            <input type="text" v-model="form.variant_1.label" placeholder="Masukan nama variasi, contoh : warna, type barang, dll">
                                        </div>
                                        <div class="form-group" v-for="(item , index) in form.variant_1.variant" :key="index">
                                            <h5>pilihan</h5>
                                            <div class="option">
                                                <input type="text" v-model="item.option" placeholder="Masukan pilihan variasi, contoh : merah, premium, dll">
                                                <img  v-if="Object.keys(form.variant_1.variant).length > 1" @click="remove_variant_1(item)" src="/img/ic_trash.svg" alt="">
                                            </div>
                                        </div>
                                        <button class="btn-secondary mtop-20" style="padding:0 20px;" @click="add_variant_1()">
                                            <img src="/img/ic_plus_vendor.svg" alt="">
                                            Tambah pilihan
                                        </button>
                                    </div>
                                </div>
                                <div class="input-variation" v-if="variant_1_status == true && variant_2_status == false">
                                    <div class="add-variation">
                                        <button style="width: 100%" class="btn-secondary" @click="active_variant_2()">
                                            <img src="/img/ic_plus_vendor.svg" alt="">
                                            tambah variasi 2
                                        </button>
                                    </div>
                                </div>
                                <div class="input-variation" v-if="variant_2_status == true">
                                    <h5>Variasi 2</h5>
                                    <div class="add-variation">
                                        <span class="close"  @click="reset_variant_2"></span>
                                        <div class="form-group">
                                            <h5>nama</h5>
                                            <input type="text" v-model="form.variant_2.label" placeholder="Masukan nama variasi, contoh : warna, type barang, dll">
                                        </div>

                                        <div class="form-group" action="" v-for="(item , index) in form.variant_2.variant" :key="index">
                                            <h5>pilihan</h5>
                                            <div class="option">
                                                <input type="text" v-model="item.option" placeholder="Masukan pilihan variasi, contoh : merah, premium, dll">
                                                <img  v-if="Object.keys(form.variant_2.variant).length > 1" @click="remove_variant_2(item)" src="/img/ic_trash.svg" alt="">
                                            </div>
                                        </div>
                                        <button class="btn-secondary" style="padding:0 20px;" @click="add_variant_2()">
                                            <img src="/img/ic_plus_vendor.svg" alt="">
                                            tambah pilihan
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="list-variation" v-if="variant_1_status == true">
                                <h5>Daftar Variasi</h5>
                                <div class="head-variation">
                                    <ul>
                                        <li v-if="variant_1_status == true">{{form.variant_1.label}}</li>
                                        <li v-if="variant_2_status == true">{{form.variant_2.label}}</li>
                                        <li>harga</li>
                                        <li>stock</li>
                                        <li>kode variasi</li>
                                    </ul>
                                </div>
                                <form id="variant_table" class="mbottom-30">
                                    <div class="body-variation" v-for="(item , index) in form.variant_1.variant" :key="index">
                                        <div style="width: 24.85%" class="col color" v-if="variant_2_status == true">{{item.option}}</div>
                                        <div v-else class="col color">{{item.option}}</div>
                                        <div style="width:100%" v-if="variant_2_status == true">
                                            <div class="child-table" v-for="(item2 , index2) in form.variant_2.variant" :key="index2">
                                                <div class="col">{{item2.option}}</div>
                                                <div class="col">Rp<input type="text" :name="'price_'+index+'_'+index2" placeholder="Masukkan harga" value="0"></div>
                                                <div class="col"><input type="number" :name="'quantity_'+index+'_'+index2" value="0"></div>
                                                <div class="col"><input type="text" :name="'sku'+index+'_'+index2" placeholder="kode variasi"></div>
                                            </div>
                                        </div>
                                        <div class="col" v-if="variant_2_status != true">Rp<input type="text" :name="'price_'+index" placeholder="Masukkan harga"></div>
                                        <div class="col" v-if="variant_2_status != true"><input type="number"  :name="'quantity_'+index" value="0"></div>
                                        <div class="col" v-if="variant_2_status != true"><input type="text" :name="'sku_'+index" placeholder="kode variasi"></div>
                                    </div>
                                </form>
                                <div class="img-variation">
                                    <div class="add-media" v-for="(item , index) in form.variant_1.variant" :key="index">
                                        <div class="media-upload">
                                            <label :for="'file-input-variant'+index">
                                                <div class="upload-icon">
                                                    <img :class="'icon preview_variant_'+index" src="/img/ic_add.svg">
                                                    <img class="preview">
                                                </div>
                                            </label>
                                            <input :id="'file-input-variant'+index" type="file" class="add-image" :class="'variantimage'+index" @change="imageChange($event , 'img_1' , 'variant' , index)">
                                            <h6> Foto {{item.option}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="input-price">
                                    <div class="form-group">
                                        <h5>harga</h5>
                                        <input type="price" v-model="form.price">
                                    </div>
                                    <div class="form-group">
                                        <h5>stok</h5>
                                        <input type="text" name="quantity" v-model="form.stock" class="qty">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="input-weight">
                                <div class="form-group">
                                    <h5>berat</h5>
                                    <input type="text" name="weight" v-model.trim="$v.form.weight.$model" placeholder="Masukkan berat barang, contoh : 100 gram">
                                    <!-- <div class="validation-error" v-if="!$v.form.weight.required && showError == true">Field is required</div> -->
                                    <small>dalam satuan gram</small>
                                </div>
                                <div class="form-group">
                                    <h5>dimensi</h5>
                                    <input type="text" name="dimesion" v-model.trim="$v.form.dimension.$model" placeholder="P X L X T">
                                    <!-- <div class="validation-error" v-if="!$v.form.dimension.required && showError == true">Field is required</div> -->
                                    <small>p x l x t (satuan dalam cm)</small>
                                </div>
                            </div>
                            <div class="input-sku">
                                <div class="form-group">
                                    <h5>sku induk</h5>
                                    <input type="text" name="sku" v-model.trim="$v.form.sku.$model">
                                    <!-- <div class="validation-error" v-if="!$v.form.sku.required && showError == true">Field is required</div> -->
                                </div>
                            </div>
                            <div class="col select-option" style="width:20%">
                                <h5>status</h5>
                                <!-- <div class="select" tabindex="1">
                                    <input v-model="form.status" class="selectopt" name="status" type="radio" id="c1" value="active" checked>
                                    <label for="c1" class="option">aktif</label>
                                    <input v-model="form.status" class="selectopt" name="status" type="radio" id="c2" value="inactive">
                                    <label for="c2" class="option">tidak aktif</label>
                                </div> -->
                                <select class="select-filter" v-model="form.status" tabindex="1">
                                    <option value="active">aktif</option>
                                    <option value="inactive">tidak aktif</option>
                                </select>
                                <!-- v-model="form.status"-->
                            </div>
                            <div class="button">
                                <button class="btn-secondary" @click="$router.push('list')">
                                    kembali
                                </button>
                                <button class="btn-primary" @click="createProduct()">
                                    simpan &amp; tampilkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <modal-category ref="ModalCategoryData" @setcategory="setcategory($event)"></modal-category>
    </div>
</template>
<style scoped>
.validation-error{
    font-size: 12px;
    padding: 3px 2px;
    color: red
}
</style>
<script>
    import Vue from 'vue'
    import Vuelidate from 'vuelidate'
    import Select2 from 'v-select2-component';
    import { VueEditor } from "vue2-editor";
    import apiVendor from '../../apis/Vendor'
    import ModalCategory from '../desktop/modal/ModalCategory.vue'
    Vue.use(Vuelidate)
    import { required, minLength, between } from 'vuelidate/lib/validators'
    import Api from '../../apis/Api';

    export default {
        data(){
            return {
                form : {
                    name : '',
                    category : '',
                    brand : '',
                    description : '',
                    picture : [],
                    all_variant : [],
                    variant_1 : { label : '' , variant :[]},
                    variant_2 : { label : '' , variant :[]},
                    picture_variant : [],
                    weight : '',
                    dimension : '',
                    sku : '',
                    price : 0,
                    stock : 0,
                    status : '',
                },
                variant : false,
                variant_1_status : false,
                variant_2_status : false,
                video : '',
                image_product : {
                    img_1 : '',
                    img_2 : '',
                    img_3 : '',
                    img_4 : '',
                    img_5 : '',
                    img_6 : '',
                },
                image_variant : [],
                submitButton : true,
                showError : false,
                category : {},
                category_name : '',
                maxCount: 100,
                remainingCount: 100,
                message: '',
                hasError: false,
                maxCounts: 100,
                remainingCounts: 100,
                messages: '',
                hasErrors: false,

            }
        },
        validations: {
            form : {
                brand: {
                    //required,
                    //minLength: minLength(4),
                },
                dimension: {
                    //required,
                    //minLength: minLength(4),
                },
                name: {
                    //required,
                    //minLength: minLength(4),
                },
                sku: {
                    //required,
                    //minLength: minLength(4),
                },
                weight: {
                    //required,
                    //minLength: minLength(4),
                },
                description: {
                    //required,
                    //minLength: minLength(4),
                }
            },
        },
        mounted(){
        },
        methods :{
            setcategory(event){
                this.form.category = event.id
                this.category_name = event.name
            },
            show_category_modal(){
                this.$refs.ModalCategoryData.show_modals();
            },
            imageChange(event , img , type, index = null){
                if(type == 'photo'){
                    if (event) {
                        $('.preview_'+img).attr('src' , URL.createObjectURL(event.target.files[0]));
                    }
                    this.image_product[img] = event.target.files[0]
                }else if(type == 'video'){
                    this.video = event.target.files[0]
                }else if(type == 'variant'){
                    if (event) {
                        $('.preview_variant_'+index).attr('src' , URL.createObjectURL(event.target.files[0]));
                    }
                    if(this.image_variant[index] !== undefined){
                        this.image_variant[index] = event.target.files[0]
                    }else{
                        this.image_variant.push(event.target.files[0])
                    }
                }
            },
            createProduct(){
                this.showError = false
                if(this.submitButton == true){
                    this.submitButton = false;
                    if (this.$v.$invalid) {
                        this.submitButton = true;
                    }else {
                        const fd = new FormData();
                        for( let i=0; i< 6; i++){
                            fd.append('image_product[]' , this.image_product['img_'+(i+1)])
                        }
                        for( let i=0; i < Object.keys(this.form.variant_1.variant).length; i++){
                            if(this.image_variant[i] === undefined){
                                fd.append('image_variant[]' , '')
                            }else{
                                fd.append('image_variant[]' , this.image_variant[i])
                            }

                        }
                        fd.append('form' , JSON.stringify(this.form));
                        fd.append('varianttable' , $('#variant_table').serialize());
                        fd.append('video' , this.video)
                        apiVendor.createProduct(fd).then( response => {
                            this.submitButton = true;
                            if(response.data.code == 200){
                                // window.location.reload()
                                window.location.href = "list"
                            }else{
                                alert(response.data.message);
                            }
                        });
                    }
                }
            },
            add_variant_1(){
                this.form.variant_1.variant.push({option : '' , price : 0 , stock : 0});
            },
            add_variant_2(){
                this.form.variant_2.variant.push({option : '' , price : 0 , stock : 0});
            },
            active_variant(){
                this.variant = true;
                this.variant_1_status = true;
                this.form.variant_1.variant.push({option : '' , price : 0 , stock : 0});
            },
            active_variant_2(){
                this.variant_2_status = true;
                this.form.variant_2.variant.push({option : '' , price : 0 , stock : 0});
            },
            variant_image(c){
                $('.'+c).click()
            },
            reset_variant_2(){
                this.variant_2_status = false;
                this.form.variant_2.variant = [];
            },
            reset_variant(){
                this.form.variant_1.variant = [];
                this.form.variant_2.variant = [];
                this.variant = false;
                this.variant_2_status = false;
                this.variant_1_status = false;
            },
            remove_variant_1(v){
                this.form.variant_1.variant.splice(v , 1)
            },
            remove_variant_2(v){
                this.form.variant_2.variant.splice(v , 1)
            },
            countdown: function() {
                this.remainingCount = this.maxCount - this.message.length;
                this.hasError = this.remainingCount < 0;
            },
            countdowns: function() {
                this.remainingCounts = this.maxCounts - this.messages.length;
                this.hasErrors = this.remainingCounts < 0;
            }
        },
        components :{
            Select2,
            ModalCategory,
            VueEditor
        }
    }
</script>
