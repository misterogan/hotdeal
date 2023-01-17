<template>
    <div class="product-detail">
        <div class="ph-container-fluid" v-if="is_loading">
            <div class="ph-desktop">
                <div class="ph-row">
                    <div class="ph-col-4">
                        <div class="ph-h400 bg-placeholder mright-10 mbottom-30 rounded"></div>
                    </div>
                    <div class="ph-col-4">
                        <div class="ph-h400 bg-placeholder mright-10 mbottom-30 rounded"></div>
                    </div>
                    <div class="ph-col-4">
                        <div class="ph-h400 bg-placeholder mright-10 mbottom-30 rounded"></div>
                    </div>
                </div>
                <div class="ph-row">
                    <div class="ph-col-3">
                        <div class="ph-h50 bg-placeholder mbottom-10"></div>
                    </div>
                </div>
                <div class="ph-row">
                    <div class="ph-col-8">
                            <div class="ph-h500 bg-placeholder mbottom-10 rounded"></div>
                        </div>
                    </div>
                </div>
            <div class="ph-mobile">
                <div class="ph-row">
                    <div class="ph-col-12">
                        <div class="ph-h350 bg-placeholder mbottom-15 rounded"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-hd tab-column" v-if="!is_loading">
            <div class="col-8 col-md-12">
                <div class="row-hd tab-column" v-if="Object.keys(product).length > 0">
                    <div class="col-6 col-md-12 slide mright-20 relative">
                        <VueSlickCarousel class="slider-small" :arrows="true" :dots="true" :autoplay="false" :infinite="true">
                            <div class="product-slide" v-for="(image , index) in product.galleries" :key="index">
                                <video v-if="image.type === 'video'" style="overflow:hidden;" height="100%" width="100%" controls :src="image.url" controlsList="nodownload" preload="metadata"></video>
                                <img v-else :src="image.url" :alt="image.variant" :class="'var-gal var-' + image.variant" :data-index="index">
                            </div>
                        </VueSlickCarousel>
                        <div class="btn-mobile d-none absolute">
                            <!-- <img class="zoom" src="img/detail-zoom.svg" alt=""> -->
                            <button class="share" @click="show_modal_share" :data-link='"/product-detail/" + product.slug' :data-title='product.name'>
                                <img src="/img/detail-share-mobile.svg" alt="">
                            </button>
                        </div>
                    </div>
                    <div class="col-6 col-md-12 desc-product">
                        <div class="coldesc-1">
                            <s class="fs-grey fs-18 fw-400" v-if="product.discount.value > 0"> {{ product.label_amount }} </s>
                            <div class="d-flex align-center mbottom-10">
                                <h2 id="second" class="fp-pink mright-5" v-if="price_selected > 0 ">Rp {{price_selected | NumberFormat}}</h2>
                                <h2 id="second" class="fp-pink mright-5" v-else>{{price_selected}}</h2>
                                <div class="discount" v-if="product.discount.value > 0">
                                    {{product.discount.value}}%
                                </div>
                            </div>
                            <div class="d-flex align-center">
                                <h5 class="fs-black mbottom-5 fs-16">{{product.name}}</h5>
                            </div>
                            <div class="d-flex align-center">
                                <rating class="mright-5" :rating="product.rating" :count_rating="product.total_review"></rating>
                                <span class="fs-black fs-12 mright-5" v-if="product.total_review > 0">({{product.total_review}})</span>
                                <b class="fs-black fs-12" v-if="product.sold > 0">Terjual {{product.sold}}</b>
                            </div>
                            <div class="variation-mobile d-none" v-if="isMobile()">
                                <div class="variation radio mbottom-10" v-if="Object.keys(product.detail.variant_value_1).length > 0">
                                    <div class="d-flex align-center">
                                        <h5 class="fs-black">Pilihan {{product.detail.variant_key_1 }} :</h5>&nbsp;
                                        <span class="fp-white fw-300">{{variant_count_1}} {{product.detail.variant_key_1 }}</span>
                                    </div>
                                    <div class="d-flex align-center flex-wrap" v-if="Object.keys(product.detail.variant_value_1).length > 0">
                                        <div class="input-radio" v-for="(item, index ) in product.detail.variant_value_1" :key="index+'key1'">
                                            <div class="radio">
                                                <input v-model="item_key_1" type="radio" name="variant" :id="index+'key1'" @change="selected_item_key_1(item)">
                                                <label :for="index+'key1'"></label>
                                            </div>
                                            <label class="fs-black fw-500 pointer mleft-25" :for="index+'key1'">{{item}}</label>
                                        </div>
                                    </div>
                                </div> 
                                <div class="variation select mbottom-10" v-if="Object.keys(product.detail.variant_value_2).length > 0">
                                    <div class="d-flex align-center mbottom-10">
                                        <h5>Pilihan {{product.detail.variant_key_2}} :</h5>&nbsp;
                                        <span class="fp-white fw-300">{{variant_count_2}} {{product.detail.variant_key_2}}</span>
                                    </div>
                                    <div class="select-box" v-if="Object.keys(product.detail.variant_value_2).length > 0">
                                        <div class="selected default" id="selected-variant">
                                            {{variant_selected_2 === '' ? 'Pilih ' + product.detail.variant_key_2 : variant_selected_2}}
                                        </div>
                                        <div class="options-container">
                                            <div class="option" v-for="(item, index ) in product.detail.variant_value_2" :key="index+'key2'" @click="selected_item_key_2(item)">
                                                <input type="radio" class="radio" :for="index+'key1'" name="variant_2" v-model="item_key_2">
                                                <label :for="index+'key1'">{{item}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="variation qty">
                                    <div class="d-flex align-center mbottom-10">
                                        <h5 class="fs-black">Jumlah Pesanan :</h5>
                                        <h5 class="fs-black fw-300 mleft-5">{{quantity}} pc</h5>
                                    </div>
                                    <div class="d-flex align-center justify-between">
                                        <div class="quantity">
                                            <button class="minus-btn" type="button" name="button" @click="decQuantity()">
                                                <img src="/img/ic_minus.svg" alt="" />
                                            </button>
                                            <input type="number" name="name" id="quantity" class="qty" v-model="quantity"
                                                oninput="javascript: if (this.value < this.maxLength) this.value = this.value.slice(0, this.maxLength); if (this.value < 1) this.value = 1;"
                                                maxlength = "6"
                                                min = "1"/>
                                            <button class="plus-btn" type="button" name="button" @click="addQuantity()">
                                                <img src="/img/icon_plus.svg" alt="" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="coldesc-2">
                            <div class="d-flex align-center mbottom-15">
                                <div class="d-flex align-start mright-20">
                                    <img width="15" class="mtop-5 mright-5" src="/img/ic_stock.svg" alt="">
                                    <div class="d-flex flex-column">
                                        <b class="fs-black">Tersedia</b>
                                        <span class="fs-black">{{product.total_stock}} pc</span>
                                    </div>
                                </div>
                                <div class="d-flex align-start mright-20">
                                    <img width="18" class="mtop-5 mright-5" src="/img/ic_weight.svg" alt="">
                                    <div class="d-flex flex-column">
                                        <b class="fs-black">Berat</b>
                                        <span class="fs-black">{{product.weight}}gr</span>
                                    </div>
                                </div>
                                <div class="d-flex align-start">
                                    <img width="10" class="mtop-5 mright-5" src="/img/ic_loc.svg" alt="">
                                    <div class="d-flex flex-column">
                                        <b class="fs-black">Dikirim dari</b>
                                        <span class="fs-black">{{product.vendor.city}}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- <router-link :to="'/merchant-product?merchant='+product.vendor.vendor_id"> -->
                            <router-link :to="'/merchant/'+product.vendor.slug+'?p=products'">
                                <div class="d-flex align-center seller-info">
                                    <img :src="product.vendor.image" :alt="product.vendor.vendor_name">
                                    <div>
                                        <h6 class="fs-black">{{product.vendor.vendor_name}}</h6>
                                        <h6 class="fs-black fw-400">{{product.vendor.city}}, {{product.vendor.province}}</h6>
                                    </div>
                                </div>
                            </router-link>
                        </div>
                        <div class="coldesc-3">
                            <h6 class="fs-black mbottom-5">deskripsi produk</h6>
                            <div class="text-description active" v-html="product.description" ></div>
                            <button @click="readMore()" v-if="text_height >= 30" class="btn-link readmore"></button>
                            <div class="d-flex align-center">
                                <b class="fs-black fs-12">Kategori :</b>&nbsp;
                                <router-link class="fp-purple fw-600 fs-12" :to="'/product?search='+product.category">{{product.category}}</router-link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-hd rekomendasi mtop-30">
                    <div class="box-review-produk" v-if="Object.keys(product).length > 0">
                        <review :review="product.reviews" :product_id="product.id"></review>
                    </div>
                </div>
            </div>
            <div class="col-4 mleft-20">
                <div class="quantity-product">
                    <div class="box-purple">
                        <div class="variation radio mbottom-10" v-if="Object.keys(product.detail.variant_value_1).length > 0">
                            <div class="d-flex align-center">
                                <h5>Pilihan {{product.detail.variant_key_1 }} :</h5>&nbsp;
                                <span class="fp-white fw-300">{{variant_count_1}} {{product.detail.variant_key_1 }}</span>
                            </div>
                            <div class="d-flex align-center flex-wrap" v-if="Object.keys(product.detail.variant_value_1).length > 0">
                                <div class="input-radio" v-for="(item, index ) in product.detail.variant_value_1" :key="index+'key1'">
                                    <div class="radio">
                                        <input v-model="item_key_1" type="radio" name="variant" :id="index+'key1'" @change="selected_item_key_1(item)">
                                        <label :for="index+'key1'"></label>
                                    </div>
                                    <label class="pointer mleft-25" :for="index+'key1'">{{item}}</label>
                                </div>
                            </div>
                        </div> 
                        <div class="variation select mbottom-10" v-if="Object.keys(product.detail.variant_value_2).length > 0">
                            <div class="d-flex align-center mbottom-10">
                                <h5>Pilihan {{product.detail.variant_key_2}} :</h5>&nbsp;
                                <span class="fp-white fw-300">{{variant_count_2}} {{product.detail.variant_key_2}}</span>
                            </div>
                            <div class="select-box" v-if="Object.keys(product.detail.variant_value_2).length > 0">
                                <div class="d-flex align-center w-100">
                                    <div class="selected default w-95" id="selected-variant">
                                        {{variant_selected_2 === '' ? 'Pilih ' + product.detail.variant_key_2 : variant_selected_2}}
                                    </div>
                                    <img class="ic-dropdown pointer mleft-5" src="/img/dropdwn_var.svg" width="26" alt="" />
                                </div>
                                <div class="options-container">
                                    <div class="option" v-for="(item, index ) in product.detail.variant_value_2" :key="index+'key2'" @click="selected_item_key_2(item)">
                                        <input type="radio" class="radio" :for="index+'key1'" name="variant_2" v-model="item_key_2">
                                        <label :for="index+'key1'">{{item}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="variation qty">
                            <div class="d-flex align-center mbottom-10">
                                <h5>Jumlah Pesanan :</h5>&nbsp;
                                <span class="fp-white">{{quantity}} pc</span>
                            </div>
                            <div class="d-flex align-center justify-between">
                                <div class="quantity">
                                    <button class="minus-btn" type="button" name="button" @click="decQuantity()">
                                        <img src="/img/btn_minus.svg" alt="" />
                                    </button>
                                    <input type="number" name="name" id="quantity" class="qty" v-model="quantity"
                                        oninput="javascript: if (this.value < this.maxLength) this.value = this.value.slice(0, this.maxLength); if (this.value < 1) this.value = 1;"
                                        maxlength = "6"
                                        min = "1"/>
                                    <button class="plus-btn" type="button" name="button" @click="addQuantity()">
                                        <img src="/img/btn_plus.svg" alt="" />
                                    </button>
                                </div>
                                <div class="label-harga">
                                    <h5 class="mright-5 fw-300">Harga</h5>
                                    <h5 id="second" class="fw-500">{{calculated}}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="tag" v-if="product.text_promo.have_promo">
                            <div class="d-flex align-center">
                                <h5>Promo :</h5>&nbsp;
                                <span class="fp-white">Tersedia {{product.text_promo.text}}</span>
                            </div>
                            <div class="d-flex align-center mtop-10">
                                <div class="free-shipping" v-if="product.is_gratis_ongkir"></div>
                                <div class="voucher-available" v-if="product.in_voucher"></div>
                                <div class="badge-rejeki-nomplok" v-if="product.in_rejeki_nomplok"></div>
                            </div>
                        </div>
                        <div class="row-btn" id="floating_btn">
                            <div class="btn">
                                <add-to-cart :key_item_1='item_key_1' :quantity="quantity" :key_item_2='item_key_2' :item="product.detail.variant_data" Clases="fav" :active='product.in_wish_list' @addcountcart="countCart" @updateCarts="$emit('updateCarts' , $event)"></add-to-cart>
                                <wish-component class="detail-btn-wish" :slug='product.slug' :clases="product.in_wish_list ? 'active' : ''" :active='product.in_wish_list' @wish="product.in_wish_list = $event" :type="'big'"></wish-component>
                                <button class="d-flex detail-btn-share" @click="show_modal_share" :data-link='"/product-detail/" + product.slug' :data-title='product.name'></button>
                            </div>
                            <button class="btn-cta" @click="buyNow(item_key_1 ,quantity,item_key_2 , product.detail.variant_data)"> 
                                <img src="/img/ic_repurchase_white.svg" alt="">
                                Beli Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <product-recomendation class="mtop-30 mbottom-30" @updateCarts="$emit('updateCarts' , $event)"></product-recomendation>
        <modal-share :url="url" :products="product"></modal-share> 
    </div>
</template>

<script>
    import apiCustomer from '../../apis/Customer'
    import Customer from '../../apis/Customer'
    import VueSlickCarousel from 'vue-slick-carousel'
    import WishComponent from '../desktop/component/WishComponent.vue'
    import AddToCart from '../desktop/component/AddToCart.vue'
    import 'vue-slick-carousel/dist/vue-slick-carousel.css'
    import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
    import Modal from '../../components/desktop/Modal'
    import Rating from '../desktop/component/Rating.vue'
    import Review from './component/Review.vue'
    import HighLightImageOnly from './component/product/HighLightImageOnly.vue'
    import HighLightVideoProduct from './component/product/HighlightVideoProduct.vue'
    import ProductRecomendation from './ProductRecomendation.vue'
    import Message from '../../utils/Message'
    import ModalShare from '../desktop/modal/ModalShare.vue'
    
    export default {
        data(){
            return {
                is_loading: true,
                product : {},
                price : '',
                item_key_1 : '',
                item_key_2 : '',
                quantity : 1,
                countCart : 0,
                price_selected : 0,
                second : 2,
                url:'https://hotdeal.id/product-detail/',
                variant_selected_2 : '',
                variant_count_1 : 0,
                variant_count_2 : 0,
                reviews : {},
                text_height : 0
            }
        },
        name: "DetailProduct.vue",
        mounted(){
            this.get_product_by_slug();
            this.set_breadcrumb();
        },
        updated: 
        function(){
            let h = $('.text-description').height();
            this.text_height = h;
        },
        methods: {
            readMore(){
                $(".desc-product").toggleClass("active");
                $(".text-description").toggleClass("active");
            },
            set_breadcrumb() {
                let slug = this.$route.params.pathMatch
                let product_name = slug.replaceAll("-", " ");
                if (this.$route.meta.breadcrumb.length >= 3) {
                    this.$route.meta.breadcrumb.pop()
                }
                const arr_product_name = product_name.split(" ");
                for (var i = 0; i < arr_product_name.length; i++) {
                    arr_product_name[i] = arr_product_name[i].charAt(0).toUpperCase() + arr_product_name[i].slice(1);
                }
                document.title = arr_product_name.join(" ") + ' - Hotdeal.id';
                this.$route.meta.breadcrumb.push({name: product_name, link: '/product-detail/' + slug});
            },
            show_modal_share(){
                $("#modal_share").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_video(){
                $("#video_produk_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            get_product_by_slug(){
                Customer.get_product_by_slug({ slug : this.$route.params.pathMatch}).then(response => {
                    if(response.data.code == 200){
                        this.product = response.data.data.product;
                        this.price_selected = this.product.detail.price;
                        this.variant_count_1 = this.product.detail.variant_value_1.length;
                        this.variant_count_2 = this.product.detail.variant_value_2.length;
                        this.reviews = this.product.reviews
                    }else{
                         return this.$router.push({name:'notfound'})
                    }
                    this.is_loading = false;
                    this.url=this.url + this.product.slug;
                })
            },
            selected_item_key_1($key){
                this.item_key_1 = $key;
                let key_variant = this.item_key_1.replaceAll(' ' ,'_')+'_'+this.item_key_2.replaceAll(' ' ,'_');
                if (typeof this.product.detail.variant_data[key_variant] != "undefined") {
                    this.price_selected = this.product.detail.variant_data[key_variant].price;
                }
                $('#selected_variant_1').text($key)
                this.variant_image(this.item_key_1)
            },
            selected_item_key_2($key){
                this.item_key_2 = $key;
                let key_variant2 = this.item_key_2.replace(' ' ,'_')+'_'+this.item_key_2.replace(' ' ,'_');
                if (typeof this.product.detail.variant_data[key_variant2] != "undefined") {
                    this.price_selected = this.product.detail.variant_data[key_variant2].price;
                    this.variant_selected_2 = $key;
                }
                this.variant_selected_2 = $key;
                $(".var-cont").removeClass('active'); 
            },
            decQuantity(){
                if(this.quantity > 1){
                    this.quantity--;
                }
            },
            addQuantity(){
                 this.quantity++;
            },
            updateQuantity(){

            },
            addcart(count){
                this.countCart = count;
                this.$emit('addcountcart' , this.countCart);
            },
            buyNow(item_key_1 , quantity , item_key_2 , item){
                if(!localStorage.getItem('auth')){
                    return Message.alert('Silahkan login terlebih dahulu.', 'Informasi', 1500);
                }
                let id = item_key_1.replaceAll(' ' ,'_')+'_'+item_key_2.replaceAll(' ' ,'_');
                if(item[id] === undefined){
                    Message.alert('Pilih produk yang ingin dimasukkan kedalam keranjang', 'Informasi' , 1500);
                }else{
                    Customer.buyNow({ pdid : item[id].pdid , qty : quantity , from_add_cart : true}).then( response => {
                        if(response.data.status === 'error'){
                            Message.alert(response.data.message, 'Informasi' , 1500);
                        }else{
                         window.location.href = '/cart'
                        }
                    });
                }
            },
            variant_image(val){
                let variant_image = (val.replaceAll(' ', '-')).toLocaleLowerCase();
                let a = $(".slick-slide" ).css('width');
                let index = $('.var-'+variant_image).data('index');
                let idx;
                if(index == undefined){
                    idx = 1
                } else {
                    idx = index + 1   
                }
                const width = '-'+a.slice(0, -2)
                const widthTranslate = parseInt(width) + (idx * parseInt(width)) - parseInt(width)
                $('.slick-track').css({"transform": "translate3d("+widthTranslate+ "px, 0px, 0px)"});
            },
            isMobile() {
                if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    return true
                } else {
                    return false
                }
            },
            
        },

        computed: {
            calculated:function(){
                if(isNaN(this.quantity * this.price_selected)){
                    const price = parseInt((this.price_selected.replace('Rp ', '')).replace('.', ''));
                    const fix_price = this.quantity * price;
                    return this.$options.filters.RupiahFormat(fix_price);
                }
                const fix_price = this.quantity * this.price_selected;
                return this.$options.filters.RupiahFormat(fix_price);
            }
        },
        components:{
            VueSlickCarousel,
            Modal,
            WishComponent,
            AddToCart,
            Rating,
            Review,
            HighLightVideoProduct,
            HighLightImageOnly,
            ProductRecomendation,
            ModalShare,
        },
    }
</script>

<style scoped>
</style>
