<template>
    <div>
        <div class="main-content">
            <div class="center-menu">
                <div class="main-menu">
                    <div class="box add-product">
                        <h3 class="mbottom-30">edit produk</h3>
                        <form v-on:submit.prevent="">
                            <div class="form-group">
                                <h5>nama produk (judul)</h5>
                                <input type="text" name="name" placeholder="Masukkan nama produk" v-model.trim="$v.form.name.$model" v-on:keyup="countdown" maxlength="100">
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
                                <input type="text" name="brand" placeholder="Masukkan merk produk" v-model.trim="$v.form.brand.$model" v-on:keyup="countdowns" maxlength="100">
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
                                                <img class="icon preview_img_1" :src="product_details.main_photo != null ? product_details.main_photo :  '/img/ic_add.svg'">
                                            </div>
                                        </label>
                                        <input id="img_1" type="file" class="add-image" ref="img_1" @change="mainImage($event)">
                                        <h6 @click="$refs.img_1.click()">foto utama</h6>
                                    </div>
                                </div>
                                <div class="add-media">
                                    <div class="media-upload">
                                        <label for="img_2">
                                            <div class="upload-icon">
                                                <img class="icon preview_img_2" :src="product_details.galleries[0] ? product_details.galleries[0].link :  '/img/ic_add.svg'">
                                            </div>
                                        </label>
                                        <input id="img_2" type="file" class="add-image" ref="img_2" @change="updateMedia($event , 'img_2', product_details.galleries[0] ? product_details.galleries[0].id : '' , 'photo')">
                                        <h6 @click="$refs.img_2.click()">foto 1</h6>
                                    </div>
                                </div>
                                <div class="add-media">
                                    <div class="media-upload">
                                        <label for="img_3">
                                            <div class="upload-icon">
                                                <img class="icon preview_img_3" :src="product_details.galleries[1] ? product_details.galleries[1].link :  '/img/ic_add.svg'">
                                                <img class="preview">
                                            </div>
                                        </label>
                                        <input id="img_3" type="file" class="add-image" ref="img_3" @change="updateMedia($event , 'img_3', product_details.galleries[1] ? product_details.galleries[1].id : '' , 'photo')">
                                        <h6 @click="$refs.img_3.click()">foto 2</h6>
                                    </div>
                                </div>
                                <div class="add-media">
                                    <div class="media-upload">
                                        <label for="img_4">
                                            <div class="upload-icon">
                                                <img class="icon preview_img_4" :src="product_details.galleries[2] ? product_details.galleries[2].link :  '/img/ic_add.svg'">
                                            </div>
                                        </label>
                                        <input id="img_4" type="file" class="add-image" ref="img_4" @change="updateMedia($event , 'img_4', product_details.galleries[2] ? product_details.galleries[2].id : '' , 'photo')">
                                        <h6 @click="$refs.img_4.click()">foto 3</h6>
                                    </div>
                                </div>
                                <div class="add-media">
                                    <div class="media-upload">
                                        <label for="img_5">
                                            <div class="upload-icon">
                                                <img class="icon preview_img_5" :src="product_details.galleries[3] ? product_details.galleries[3].link :  '/img/ic_add.svg'">
                                            </div>
                                        </label>
                                        <input id="img_5" type="file" class="add-image" ref="img_5" @change="updateMedia($event , 'img_5', product_details.galleries[3] ? product_details.galleries[3].id : '' , 'photo')">
                                        <h6 @click="$refs.img_5.click()">foto 4</h6>
                                    </div>
                                </div>
                                <div class="add-media">
                                    <div class="media-upload">
                                        <label for="img_6">
                                            <div class="upload-icon">
                                                <img class="icon preview_img_6" :src="product_details.galleries[4] ? product_details.galleries[4].link :  '/img/ic_add.svg'">
                                            </div>
                                        </label>
                                        <input id="img_6" type="file" class="add-image" ref="img_6" @change="updateMedia($event , 'img_6', product_details.galleries[4] ? product_details.galleries[4].id : '' , 'photo')">
                                        <h6 @click="$refs.img_6.click()">foto 5</h6>
                                    </div>
                                </div>
                            </div>
                            <div v-if="product_details.video != null">
                                <h5>video</h5>
                                <div class="img-variation">
                                    <div class="add-media">
                                        <div class="media-upload">
                                            <label for="file-input-vide0">
                                                <div>
                                                    <video style="overflow:hidden;" height="100%" width="100%" controls :src="product_details.video" controlsList="nodownload" preload="metadata"></video>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            <div class="input-variation" v-if="variant== false">
                                <div class="add-variation">
                                    <button style="width: 100%" class="btn-secondary" @click="active_variant()">
                                        <img src="/img/ic_plus.svg" alt="">
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
                                                <input type="text" v-model="item.option" placeholder="Masukan pilihan variasi, contoh : merah, premium, dll" @change="validateOption1($event.target.value ,index)">
                                                <img  v-if="Object.keys(form.variant_1.variant).length > 1" @click="remove_variant_1(item , index)" src="/img/ic_trash.svg" alt="">
                                            </div>
                                        </div>
                                        <span id="error_variant_1" style="color:red;display:none">Pilihan tidak boleh sama</span>
                                        <button class="btn-secondary mtop-20" @click="add_variant_1()">
                                            <img src="/img/ic_plus.svg" alt="">
                                            Tambah pilihan
                                        </button>
                                    </div>
                                </div>
                                <div class="input-variation" v-if="variant_1_status == true && variant_2_status == false">
                                    <div class="add-variation">
                                        <button style="width: 100%" class="btn-secondary" @click="active_variant_2()">
                                            <img src="/img/ic_plus.svg" alt="">
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
                                            <input type="text" v-model="form.variant_2.label" @change="detailVariant()" placeholder="Masukan nama variasi, contoh : warna, type barang, dll">
                                        </div>

                                        <div class="form-group" action="" v-for="(item , index) in form.variant_2.variant" :key="index">
                                            <h5>pilihan</h5>
                                            <div class="option">
                                                <input type="text" v-model="item.option" placeholder="Masukan pilihan variasi, contoh : merah, premium, dll" @change="validateOption2($event.target.value ,index)">
                                                <img  v-if="Object.keys(form.variant_2.variant).length > 1" @click="remove_variant_2(item , index)" src="/img/ic_trash.svg" alt="">
                                            </div>
                                        </div>
                                        <span id="error_variant_2" style="color:red;display:none">Pilihan tidak boleh sama</span>
                                        <button class="btn-secondary" @click="add_variant_2()">
                                            <img src="/img/ic_plus.svg" alt="">
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
                                        <div style="width:24.85%" class="col color" v-if="variant_2_status == true">{{item.option}} </div>
                                        <div v-else class="col color">{{item.option}}</div>
                                        <div style="width:100%;" v-if="variant_2_status == true">
                                            <div class="child-table" v-for="(item2 , index2) in form.variant_2.variant" :key="index2">
                                                <div class="col">{{item2.option}}</div>
                                                <div class="col">Rp<input type="text" :name="'price_'+index+'_'+index2" v-if="form.all_variant[item.option+'_'+item2.option] != undefined" placeholder="Masukkan harga" v-model="form.all_variant[item.option+'_'+item2.option].price"></div>
                                                <div class="col"><input type="number" :name="'quantity_'+index+'_'+index2" v-if="form.all_variant[item.option+'_'+item2.option] != undefined" value="0" v-model="form.all_variant[item.option+'_'+item2.option].quantity"></div>
                                                <div class="col"><input type="text" :name="'code'+index+'_'+index2" v-if="form.all_variant[item.option+'_'+item2.option] != undefined" value="0" placeholder="kode variasi" v-model="form.all_variant[item.option+'_'+item2.option].sku"></div>
                                            </div>
                                        </div>
                                        <div class="col" v-if="variant_2_status != true">Rp<input type="text" :name="'price_'+index" placeholder="Masukkan harga" v-if="form.all_variant[item.option+'_'] != undefined" v-model="form.all_variant[item.option+'_'].price"></div>
                                        <div class="col" v-if="variant_2_status != true"><input type="number"  :name="'quantity_'+index" value="0" v-if="form.all_variant[item.option+'_'] != undefined" v-model="form.all_variant[item.option+'_'].quantity"></div>
                                        <div class="col" v-if="variant_2_status != true"><input type="text" :name="'code_'+index" value="0" placeholder="kode variasi" v-if="form.all_variant[item.option+'_'] != undefined" v-model="form.all_variant[item.option+'_'].sku"></div>
                                    </div>
                                </form>
                                <div class="img-variation">
                                    <div class="add-media" v-for="(item , index) in form.variant_1.variant" :key="index">
                                        <div class="media-upload">
                                            <label :for="'file-input-variant'+index">
                                                <div class="upload-icon">
                                                    <img :class="'icon preview_variant_'+index" :src="item.picture != null || 0 ? item.picture : '/img/ic_add.svg'">
                                                    <img class="preview">
                                                </div>
                                            </label>
                                            <input type="text" :value="item.product_detail_id" :id="'product_detail_id_'+index" :data-id="item.product_detail_id">
                                            <input :id="'file-input-variant'+index" type="file" class="add-image" :class="'variantimage'+index" @change="imageChangeVariant($event , 'img_1' , 'variant' , index)" :data-id="item.product_detail_id" :data-option="item.option">
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
                            <select class="select-filter" v-model="form.status" tabindex="1">
                                <option value="active">aktif</option>
                                <option value="inactive">tidak aktif</option>
                            </select>
<!--                                v-model="form.status"-->
                            </div>
                            <div class="button">
                                <button class="btn-secondary" @click="$router.push('list')">
                                    kembali
                                </button>
                                <button class="btn-primary" @click="updateProduct()">
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
        name : 'ProductEdit',
        data(){
            return {
                form : {
                    slug:'',
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
                    product_detail_id : '',
                },
                image_product : {
                    img_2 : '',
                    img_3 : '',
                    img_4 : '',
                    img_5 : '',
                    img_6 : '',
                },
                image_product_id : {
                    img_2 : '',
                    img_3 : '',
                    img_4 : '',
                    img_5 : '',
                    img_6 : '',
                },
                variant : false,
                variant_1_status : false,
                variant_2_status : false,
                video : '',
                image_variant : [],
                v_image : [],
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
                add_variant_1_status : true,
                add_variant_2_status : true,
                product_details :{}

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
            let slug = this.$route.params.pathMatch
            this.getProductbySlug(slug)
        },
        methods :{
            getProductbySlug(slug){
                apiVendor.getProductbyVendor({slug:slug}).then( response  => {
                    this.product_details = response.data.data;
                    this.form.variant_1 = response.data.data.variant.variant_1
                    this.form.variant_2 = response.data.data.variant.variant_2
                    if(this.form.variant_1.variant.length > 0){
                        this.variant_1_status =  true
                        this.variant =  true
                    }
                    if(this.form.variant_2.variant.length > 0){
                        this.variant_2_status =  true
                    }
                    this.form.name = response.data.data.name
                    this.form.category = response.data.data.category
                    this.form.brand = response.data.data.brand
                    this.form.description = response.data.data.description
                    this.form.weight = response.data.data.weight
                    this.form.sku = response.data.data.sku
                    this.form.dimension = response.data.data.dimension

                    this.form.price = response.data.data.details[0].price
                    this.form.stock = response.data.data.details[0].stock
                    
                    this.form.status = response.data.data.status
                    this.form.slug = slug;    
                    this.form.all_variant = response.data.data.details_variant 
                    this.form.category = response.data.data.category.id
                    this.category_name = response.data.data.category.category
                    this.detailVariant()       
                })
                
            },  
            setcategory(event){
                this.form.category = event.id
                this.category_name = event.name
            },
            show_category_modal(){
                this.$refs.ModalCategoryData.show_modals();

            },
            mainImage(event){
                const fd = new FormData();
                fd.append('image' , event.target.files[0])
                fd.append('slug' ,this.form.slug)
                fd.append('main_image' , true)
                apiVendor.uploadImage(fd).then( response => {
                    if(response.data.code == 200){
                        this.product_details.main_photo = response.data.data;
                        this.main_photo = response.data.data
                    }else{
                        alert(response.data.message)
                    }
                })
            },
            imageChange(event){
                if(Object.keys(this.product_details.galleries).length > 6){
                    return alert('Maksimal gambar adalah 6')
                }
                const fd = new FormData();
                fd.append('image' , event.target.files[0])
                fd.append('slug' ,this.form.slug)
                apiVendor.uploadImage(fd).then( response => {
                    if(response.data.code == 200){
                        this.product_details.galleries.push(response.data.data);
                    }else{
                        alert(response.data.message)
                    }
                });
            },
            imageChangeVariant(event , img , type, index = null){
                if(type == 'variant'){
                    if (event) {
                        $('.preview_variant_'+index).attr('src' , URL.createObjectURL(event.target.files[0]));
                    }
                    if(this.image_variant[index] !== undefined){
                        this.image_variant[index] = event.target.files[0]
                    }else{
                        if(Object.keys(this.product_details.galleries).length > 6){
                            return alert('Maksimal gambar adalah 6')
                        } else {
                            this.image_variant.push(event.target.files[0])
                        }
                    }
                }
                const button = document.querySelector('#product_detail_id')
                const data_id = event.target.getAttribute("data-id")
                const data_option = event.target.getAttribute("data-option")
                const fd = new FormData();
                fd.append('image' , event.target.files[0])
                fd.append('slug' ,this.form.slug)
                fd.append('product_detail_id', data_id)
                fd.append('option', data_option)
                apiVendor.uploadImage(fd).then( response => {
                    if(response.data.code == 200){
                        this.v_image.push([data_option , response.data.data])
                    }else{
                        alert(response.data.message)
                    }
                });
            },
            updateMedia(event , img , id, type, index = null){
                if (img == 'main_photo'){
                    $('.preview_'+img).attr('src' , URL.createObjectURL(event.target.files[0]));
                    this.main_photo = event.target.files[0]
                } else{
                    if(type == 'photo'){
                        if (event) {
                            $('.preview_'+img).attr('src' , URL.createObjectURL(event.target.files[0]));
                        }
                        this.image_product[img] = event.target.files[0]
                        this.image_product_id[img] = id

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
                }
            },
            updateProduct(){
                this.showError = false
                if(this.submitButton == true){
                    this.submitButton = false;
                    if (this.$v.$invalid) {
                        this.submitButton = true;
                    }else {
                        const fd = new FormData();
                        fd.append('form' , JSON.stringify(this.form));
                        for( let i=1; i< 6; i++){
                            fd.append('image_product[]' , this.image_product['img_'+(i+1)])
                        }
                        for( let i=1; i< 6; i++){
                            fd.append('image_product_id[]' , this.image_product_id['img_'+(i+1)])
                        }
                        for( let i=0; i< this.form.variant_1.variant.length; i++){
                            fd.append('image_variant[]' , this.v_image[i])
                        }
                        fd.append('main_photo', this.main_photo)
                        apiVendor.updateProduct(fd).then( response => {
                            this.submitButton = true;
                            if(response.data.code == 200){
                                window.location.reload()
                            }else{
                                alert('Error saat menyimpan data');
                            }
                        });
                    }
                }
            },
            add_variant_1(){
                if(this.add_variant_1_status == true){
                    this.form.variant_1.variant.push({option : '' , price : 0 , stock : 0});
                    this.detailVariant()
                }
                
            },
            add_variant_2(){
                if(this.add_variant_2_status == true){
                    this.form.variant_2.variant.push({option : '' , price : 0 , stock : 0});
                    this.detailVariant()
                }
                //this.detailVariant()
            },
            active_variant(){
                this.variant = true;
                this.variant_1_status = true;
                this.form.variant_1.variant.push({option : '' , price : 0 , stock : 0});
                 this.detailVariant()
            },
            active_variant_2(){
                this.variant_2_status = true;
                this.form.all_variant = []
                this.form.variant_2.variant.push({option : '' , price : 0 , stock : 0});
                this.detailVariant()
            },
            variant_image(c){
                $('.'+c).click()
            },
            reset_variant_2(){
                this.variant_2_status = false;
                this.form.variant_2.variant = [];
                this.form.all_variant = []
                 this.detailVariant()
            },
            reset_variant(){
                this.form.variant_1.variant = [];
                this.form.variant_2.variant = [];
                this.variant = false;
                this.variant_2_status = false;
                this.variant_1_status = false;
                this.form.all_variant = []
                 this.detailVariant()
            },
            remove_variant_1(v, index){
                $('#error_variant_1').hide();
                this.form.variant_1.variant.splice(index , 1)
                // this.detailVariant()
            },
            remove_variant_2(v , index){
                $('#error_variant_2').hide();
                this.form.variant_2.variant.splice(index , 1)
                // this.detailVariant()
            },
            countdown: function() {
                this.remainingCount = this.maxCount - this.message.length;
                this.hasError = this.remainingCount < 0;
            },
            countdowns: function() {
                this.remainingCounts = this.maxCounts - this.messages.length;
                this.hasErrors = this.remainingCounts < 0;
            },
            validateOption1(value , index){
                $('#error_variant_1').hide();
                let arr = this.form.variant_1.variant
                var length = 0;
                Object.keys(arr).forEach(function(index) {
                    if (arr[index].option == value) {
                        length++                        
                    }
                });
                if(length > 1){
                    this.add_variant_1_status = false;
                    $('#error_variant_1').show();
                }else{
                    this.add_variant_1_status = true;
                }
                const fd = new FormData();
                fd.append('slug', this.form.slug)
                fd.append('option' ,value)
                apiVendor.updateVariant(fd).then( response => {
                    if(response.data.code == 200){
                    }else{
                        alert(response.data.message)
                    }
                });
                this.detailVariant()
            },
            validateOption2(value , index){ 
                $('#error_variant_2').hide();
                let arr = this.form.variant_2.variant
                var length = 0;
                Object.keys(arr).forEach(function(index) {
                    if (arr[index].option == value) {
                        length++   
                    }
                });
                if(length > 1){
                    this.add_variant_2_status = false;
                    $('#error_variant_2').show();
                    this.detailVariant()
                }else{
                    this.add_variant_2_status = true;
                    this.detailVariant()
                }
                
            },
            detailVariant(){
                let variant_1 = this.form.variant_1.variant
                let variant_2 = this.form.variant_2.variant
                for(let i=0; i < Object.keys(variant_1).length; i++){
                    if( Object.keys(variant_2).length > 0){
                        for(let j=0; j < Object.keys(variant_2).length; j++){
                            if(this.form.all_variant[this.form.variant_1.variant[i].option+'_'+this.form.variant_2.variant[j].option] == undefined){
                                this.form.all_variant[this.form.variant_1.variant[i].option+'_'+this.form.variant_2.variant[j].option] = {option : '' , price : 0 , stock : 0 , sku : ''};
                            }
                        }
                    }else{
                        if(this.form.all_variant[this.form.variant_1.variant[i].option+'_'] == undefined){
                            this.form.all_variant[this.form.variant_1.variant[i].option+'_'] = {option : '' , price : 0 , stock : 0 , sku : ''};
                        }
                    }
                }
            },
            deleteImage(id , index){
                apiVendor.deleteImage({id : id}).then( response => {
                    if(response.data.code == 200){
                        this.product_details.galleries.splice(index , 1);
                    }else{
                        alert(response.data.data.message)
                    }
                });
            }
        },
        computed :{
            
        },
        components :{
            Select2,
            ModalCategory,
            VueEditor
        }
    }
</script>
