<template>
    <div>
        <div v-if="is_loading">
            <div class="ph-desktop">
                <div class="ph-container d-flex">
                    <div class="col-8 mright-10 ph-box-shadow">
                        <div class="ph-row justify-between mbottom-25">
                            <div class="ph-col-4 ph-h40 bg-placeholder"></div>
                        </div>
                        <div class="ph-row mbottom-20">
                            <div class="ph-col-1 ph-h30 bg-placeholder mright-10"></div>
                            <div class="ph-col-4 ph-h50 bg-placeholder"></div>
                        </div>
                        <div class="ph-row justify-between mbottom-15">
                            <div class="ph-col-1 ph-h30 bg-placeholder mright-10"></div>
                            <div class="ph-col-2 ph-h100 bg-placeholder mright-10"></div>
                            <div class="ph-col-6 ph-h100 bg-placeholder mright-10"></div>
                            <div class="ph-col-3 ph-h100 bg-placeholder"></div>
                        </div>
                        <div class="ph-row justify-between mbottom-15">
                            <div class="ph-col-1 ph-h30 bg-placeholder mright-10"></div>
                            <div class="ph-col-2 ph-h100 bg-placeholder mright-10"></div>
                            <div class="ph-col-6 ph-h100 bg-placeholder mright-10"></div>
                            <div class="ph-col-3 ph-h100 bg-placeholder"></div>
                        </div>
                        <div class="ph-row justify-between mbottom-15">
                            <div class="ph-col-1 ph-h30 bg-placeholder mright-10"></div>
                            <div class="ph-col-2 ph-h100 bg-placeholder mright-10"></div>
                            <div class="ph-col-6 ph-h100 bg-placeholder mright-10"></div>
                            <div class="ph-col-3 ph-h100 bg-placeholder"></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="ph-col-12 ph-h400 bg-placeholder rounded"></div>
                    </div>
                </div>
            </div> 
            <div class="ph-mobile">
                <div class="ph-container">
                    <div class="ph-row justify-between mbottom-15">
                        <div class="ph-col-5 ph-h40 bg-placeholder"></div>
                        <div class="ph-col-2 ph-h20 bg-placeholder"></div>
                    </div>
                    <div class="ph-col-3 ph-h50 mbottom-20 bg-placeholder"></div>
                    <div class="ph-col-12 ph-h150 p-20 rounded ph-box-shadow mbottom-10">
                        <div class="ph-row justify-between mbottom-25">
                            <div class="ph-col-6 mright-5 ph-h90 rounded-sm bg-placeholder"></div>
                            <div class="ph-col-4 mright-5 ph-h110 bg-placeholder"></div>
                            <div class="ph-col-1 ph-h20 rounded-sm bg-placeholder"></div>
                        </div>
                    </div>
                    <div class="ph-col-12 ph-h150 p-20 rounded ph-box-shadow">
                        <div class="ph-row justify-between mbottom-25">
                            <div class="ph-col-6 mright-10 ph-h90 rounded-sm bg-placeholder"></div>
                            <div class="ph-col-4 mright-10 ph-h110 bg-placeholder"></div>
                            <div class="ph-col-1 ph-h20 rounded-sm bg-placeholder"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-hd cart" v-if="!is_loading">
            <div class="col-8 col-sm-12 card-lg relative">
                <h3 class="cart-title">Keranjang Kamu</h3>
                <div v-if="Object.keys(this.cart).length > 0">
                    <div class="cart-top row-hd align-end justify-between">
                        <div class="d-mobile">
                            <h5 class="fs-black fs-12">{{cart_total}} Barang</h5>
                        </div>
                        <!-- <div class="cart-manage pointer" id="cart-content" @click="manageCart($event)"></div> -->
                    </div>
                    <div class="cart-list">   
                        <div class="d-flex align-center mbottom-20">     
                            <div class="d-flex align-center cart-checkAll pointer" @click="checkAll()">
                                <div class="hd-checklist">
                                    <input type="checkbox" v-model="incheck_All" id="checkAllCart" name="checkAll" />
                                    <label for="checkAll"></label>
                                </div>
                                <h5 class="fp-purple fs-12 mright-25">Pilih Semua</h5>
                            </div>
                            <button v-if="incheck_All" class="btn-primary-square mleft-35 del-allCart"  @click="deleteCart(0, 0, 'all')">Hapus</button>
                        </div>
                        <div v-for="(items , index1) in cart_list.cart" :key="index1">
                            <div class="row-hd cart-store">
                                <div class="col-9 col-sm-12 row-hd">
                                    <!-- <div class="hd-checklist store" >
                                        <input type="checkbox" :class="'check-prod-'+index1" :id="'store1-'+index1" @click="vendorCheck(items.vendor_id, index1, $event)" :name="'store1-'+index1" />
                                        <label :for="'store1-'+index1"  :id="'checkbox-'+index1+'-'"></label>
                                    </div> -->
                                    <div class="hd-checklist store">
                                        <input type="checkbox" :class="'check-prod-'+index1" :id="'store1-'+index1" @change="vendorCheck(items.vendor_id, index1, $event)" v-bind:checked="items.vendor_checkout" :name="'store1-'+index1" :value="items.vendor_checkout"/>
                                        <label :for="'store1-'+index1"></label>
                                    </div>
                                    <!-- <div class="hd-checklist">
                                            <input type="checkbox" :class="'check-prod-'+index1" :id="index1+'-'+index" @change="mark_checkout(index1, index , $event)" v-bind:checked="item.is_checkout" :name="'itemcheckbox'+index" :value="item.is_checkout"/>
                                            <label :for="index1+'-'+index"></label>
                                        </div> -->
                                    <router-link :to="'/merchant-product?merchant='+items.vendor_id">
                                        <img :src="items.vendor_image" alt="store-pic">
                                    </router-link>
                                    <div>
                                        <h5 class="fs-black fs-12">{{ items.vendor_name }}</h5>
                                        <span class="fs-black fs-12">{{ items.vendor_city }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-3 d-flex justify-right">
                                    <button v-if="items.vendor_checkout" :class="'btn-primary-square delStor-'+index1" :id="'delStor-'+index1" @click="deleteVendorCart(items, index1)">Hapus</button>
                                </div>
                            </div>
                            <!-- <div :id="'cart-item'+index1+'-'+index" :class="item.is_checkout ? 'cart-item checked' : 'cart-item'" v-for="(item , index) in items.products" :key="index"> -->
                            <div :id="'cart-item'+index1+'-'+index" class="cart-item" v-for="(item , index) in items.products" :key="index">
                                <div class="cart-overlay"></div> 
                                <div class="row-hd cart-details-item">
                                    <div class="d-flex col-9 col-sm-12">
                                        <!-- <div class="hd-checklist">
                                            <input :disabled ="item.product_detail.stock == 0 || item.quantity > item.product_detail.stock" type="checkbox" :id="index1+'-'+index" @change="mark_checkout(index1, index , $event)" v-bind:checked="item.is_checkout" :name="'itemcheckbox'+index" v-model="checked_item" :value="item"/>
                                            <label :for="index1+'-'+index"></label>
                                        </div> -->
                                        <div class="hd-checklist">
                                            <input type="checkbox" :class="'check-prod-'+index1" :id="index1+'-'+index" @change="mark_checkout(index1, index , $event)" v-bind:checked="item.is_checkout" :name="'itemcheckbox'+index" :value="item.is_checkout"/>
                                            <label :for="index1+'-'+index"></label>
                                        </div>
                                        <div>
                                            <img class="cart-product-img" v-if="item.product_detail.thubmnail != null" :src="item.product_detail.thubmnail.link">
                                            <img class="cart-product-img" v-else>
                                        </div>
                                        <div class="cart-details-product">
                                            <router-link :to="'/product-detail/'+item.product_detail.product.slug" class="fs-black">
                                                <h5 class="cart-product-name fs-black fs-16 mbottom-5">{{item.product_detail.product.name}}</h5>
                                            </router-link>
                                            <div class="d-flex align-center mbottom-5">
                                                <s class="fs-grey fs-16 mright-5" v-if="item.product_detail.promotion.nominal != 0"> {{ item.product_detail.label_price}}</s>
                                                <h5 class="fp-pink fs-18">{{item.product_detail.label_face_price}}</h5>&nbsp;
                                                <div class="discount mright-10" v-if="item.product_detail.promotion.value != 0">
                                                    {{parseInt(item.product_detail.promotion.value)}}%
                                                </div>
                                            </div>
                                            <div class="d-flex align-center var-weight">
                                                <div class="variant" v-if="item.product_detail.variant_k_1">
                                                    {{item.product_detail.variant_v_1}}
                                                </div>
                                                <div class="variant" v-if="item.product_detail.variant_k_2">
                                                    {{item.product_detail.variant_v_2}}
                                                </div>
                                                <b class="fs-black fs-12">Berat &nbsp;<span class="fs-black fw-400">{{ item.product_detail.product.weight}} gram</span></b>
                                                <div class="tag">
                                                    <div class="free-shipping"></div>
                                                    <div class="voucher-available"></div>
                                                    <div class="badge-rejeki-nomplok" v-if="item.product_detail.in_rejeki_nomplok == true"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 cart-option col-sm-12">
                                        <div class="quantity">
                                            <button :class="item.quantity === 1 ? 'dec-btn disabled' : 'dec-btn'" type="button" name="button" @click="decQuantity(index1, index)"></button>
                                            <input :id="'qty-'+index1+'-'+index" type="number" :name="'name'+index" :value="item.quantity"  @change="updateQuantity($event ,index1, index)"
                                                oninput="javascript: if (this.value < this.maxLength) this.value = this.value.slice(0, this.maxLength); if (this.value < 1) this.value = 1;"
                                                maxlength = "6" 
                                                min = "1"/>
                                            <button class="inc-btn" :class="item.quantity >= item.product_detail.stock ? 'dec-btn disabled' : 'dec-btn'" type="button" name="button" @click="addQuantity(index1, index)"></button>
                                        </div>
                                        <div class="wish-del">
                                            <wish-component :class="item.in_wish_list ? 'cart-wishlist active' : 'cart-wishlist'" :slug="item.product_detail.product.slug" :clases="item.in_wish_list ? 'active' : ''"></wish-component>
                                            <button class="cart-delete" @click="deleteCart(index1, index, 'item')"></button>
                                        </div>
                                    </div>
                                </div>
                                <div :class="'cart-notes cart-notes-'+index1+'-'+index" @click="show_editor_notes(index1, index, item.product_details_id)" :id="'cart-notes-'+index1+'-'+index"></div>
                                <div :class="'add-notes add-notes-'+index1+'-'+index">
                                    <input type="text" placeholder="Tulis catatan" name="notes" :id="'notes-'+index1+'-'+index" autofocus class="input-notes" @blur="getVal(item.product_details_id, $event, index1, index)">
                                    <!-- <input :value="inputAmount" @input="handleAmountInput($event)" placeholder="Enter amount..." type="text"> -->
                                    <!-- <input placeholder="Tulis catatan" name="notes" :id="'notes-'+index1+'-'+index" autofocus class="input-notes"></input> -->
                                    <img :id="'save-note-'+index1+'-'+index" class="save-notes" src="/img/icon-checklist-grey.svg" alt="">
                                    <img class="delete-notes" src="/img/ic_trash_blue.svg" @click="delete_editor_notes">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <h5 class="fs-black fs-12">0 Barang</h5>
                    <!-- <blank-page :message="'Keranjang Belanja Anda Kosong'" :image="'img/animation_empty_cart.svg'"></blank-page> -->
                </div>
                <div v-if="deleted_all" id="pop-over-delete" class="card-sm absolute pop-over-delete">Produk berhasil dihapus dari keranjang kamu</div>
            </div>
            <div class="col-4 col-sm-12 card-purple" >
                <h5 class="title-summary">Ringkasan Belanja</h5>
                <div v-if="Object.keys(this.checked_item).length > 0">
                    <div class="cart-summary" v-for="(item , index) in checked_item" :key="index">
                        <div class="col-8">
                            <h6 class="product-name">{{item.product_detail.product.name}}</h6>
                            <h6 class="fw-400"><span :id="'total_qty'+index">{{ item.quantity }} x</span> {{(item.product_detail.face_price) | RupiahFormat}}</h6>
                        </div>
                        <div class="col-4">
                            <h6 class="white-nowrap text-right">{{(item.product_detail.face_price * item.quantity) | RupiahFormat}}</h6>
                        </div>
                    </div>
                    <div class="row-hd align-center justify-between cart-total-product mbottom-10">
                        <h6>Total Barang {{checkout_total}}</h6>
                        <h6 class="total">{{TotalPayment | RupiahFormat}}</h6>
                    </div>
                    <!-- <div class="row-hd align-center justify-between mbottom-15">
                        <h6>Total Diskon</h6>
                        <h6 class="total">{{TotalPayment | RupiahFormat}}</h6>
                    </div> -->
                    <div class="row-hd align-center justify-between cart-total">
                        <h6>Total Harga</h6>
                        <h6 class="total">{{TotalPayment | RupiahFormat}}</h6>
                    </div>
                    <!-- <router-link to="/promo-list" class="row-hd align-center justify-between cart-discount" id="floating_btn">
                        <img class="ic-promo" src="/img/cart_promo.svg" alt="" width="30">
                        <div class="d-flex align-start flex-column">
                            <h6 class="use-voucher"></h6>
                            <span class="fs-12 white mobile-notes d-none">Ada 100 Voucher yang bisa digunakan</span>
                        </div>
                        <img class="pointer click-detail" src="/img/ic_arrow_next.svg" alt="">
                    </router-link> -->
                    <div class="cart-floating mtop-20" id="floating_btn" @click="CheckoutOrder">
                        <div class="mobile-floating d-none">
                            <b>Total Belanja</b>
                            <span>{{TotalPayment | RupiahFormat}}</span>
                        </div>
                        <button class="btn-cta m-auto">Buat Pemesanan</button>
                    </div>
                </div>
                <div v-else>
                    <h5 class="fw-400">Barang Belum dipilih</h5>
                </div>
            </div>
        </div>
        <modal-delete-product :product="checked_item" :status_deleted="deleteAll" @after_delete="updateTheCart" @updateCarts="$emit('updateCarts', $event)"></modal-delete-product>
    </div>
</template>

<script>
    import ModalDeleteProduct from '../modal/ModalDeleteProduct.vue'
    import Rupiah from '../../../utils/Global'
    import CustomerAPi from '../../../apis/Customer'
    import AddItem from "../component/AddItem.vue";
    import BlankPage from "../../desktop/BlankPage.vue"
    import Message from '../../../utils/Message'
    import WishComponent from '../../desktop/component/WishComponent.vue'

    export default {
        name: "CartList",
        data(){
            return {
                cart_list : {},
                cart : {},
                quantity : 0,
                checked_item : [],
                incheck_All : false,
                deleteAll : false,
                is_loading : true,
                cart_total : 0,
                checkout_total: 0,
                deleted_all: false,
                inputAmount: '',
                vendorChecked : false,
            }
        }, 
        mounted(){
           this.cartList();
        },
        methods : {
            cartList(){
                CustomerAPi.list_cart().then( response => {
                    if('cart' in response.data.data){
                        this.cart_list = response.data.data;
                        this.cart = response.data.data.cart;
                        this.incheck_All = response.data.data.in_checkAll;
                        this.is_loading = false
                        for (const i in this.cart) {
                            for (const j in this.cart[i].products) {
                                if(this.cart[i].products[j].is_checkout === true){
                                    $('#'+i+'-'+j).prop('checked', true)
                                    this.checked_item.push(this.cart[i].products[j])
                                    this.checkout_total += parseInt(this.cart[i].products[j].quantity);
                                } else {
                                    $('#'+i+'-'+j).prop('checked', false)
                                }
                                !this.cart[i].vendor_checkout ? $('#store1-'+i).prop('checked', false) : $('#store1-'+i).prop('checked', true);
                                this.cart_total++
                            }
                        }
                    }
                })
            },
            generateVendor(){
                CustomerAPi.list_cart().then( response => {
                    if('cart' in response.data.data){
                        for (const i in this.cart) {
                            for (const j in this.cart[i].products) {
                                !this.cart[i].vendor_checkout ? $('#store1-'+i).prop('checked', false) : $('#store1-'+i).prop('checked', true);
                                
                                const check = this.checked_item.includes(this.cart[i].products[j])
                                if(!check){
                                    $('#'+i+'-'+j).prop('checked', false);
                                } else {
                                    $('#'+i+'-'+j).prop('checked', true);
                                }
                                
                            }
                        }
                    }
                })
            },
            getVal(pdid, e, idx1, idx) {
                const val = e.target.value;
                this.save_notes(pdid, val, idx1, idx)
            },
            show_editor_notes(idx1, idx, id){
                $('.cart-notes-'+idx1+'-'+idx).toggleClass('active');
                $('.add-notes-'+idx1+'-'+idx).toggleClass('active');
                let check = $('#cart-notes-'+idx1+'-'+idx).hasClass('active')
                if (!check) {
                    this.save_notes(id, null, idx1, idx);
                }
            },
            delete_editor_notes(){
                $('.add-notes').removeClass('active');
            },
            save_notes(id, notes, idx1, idx){
                CustomerAPi.saveNote({pdid: id, note: notes}).then( response => {
                    console.log(notes)
                    if(notes !== null){
                        $('#save-note-'+idx1+'-'+idx).attr('src', '/img/save-notes.svg');
                    } else{
                        $('#save-note-'+idx1+'-'+idx).attr('src', '/img/icon-checklist-grey.svg');
                    }
                })
            },
            deleteCart(index1, index, status){
                CustomerAPi.deleteCart({ pdid : [this.cart_list.cart[index1].products[index].product_details_id] , status : status  }).then( response => {
                    const qty= this.cart_list.cart[index1].products[index].quantity;
                    const idx = this.checked_item.indexOf(this.cart_list.cart[index1].products[index])

                    if(status === 'all'){
                        this.checked_item = [];
                        this.cart = {};
                        this.checkout_total = 0;
                        this.cart_total = 0;
                        this.deleted_all = true;
                        this.$emit('updateCarts' , 0)
                    } else{
                        this.deleted_all = true;
                        this.cart_list.cart[index1].products.splice(index , 1)
                        const isChecked = $('#'+index1+'-'+index).prop('checked');
                        if(isChecked){
                            this.checked_item.splice(idx, 1)
                            this.checkout_total -= parseInt(qty);
                        }
                        if((this.cart_list.cart[index1].products).length < 1){
                            this.cart.splice(index1, 1);
                        }
                        setTimeout(() => {
                            $('#pop-over-delete').fadeOut();
                            this.deleted_all = false;
                        }, 1000);
                        this.$emit('updateCarts' , response.data.data.count_cart)
                    }
                });
            },
            deleteVendorCart(data, idx){
                CustomerAPi.deleteCartVendor({ data : data , status : 'item'  }).then( response => {
                    if(response.data.code == 200){
                        let total_vendor_qty = 0;
                        for (const i in data.products) {
                            total_vendor_qty += parseInt(data.products[i].quantity);
                            const idx = this.checked_item.indexOf(data.products[i])
                            // this.checkout_total--;

                            this.checked_item.splice(idx, 1)
                        }
                        this.checkout_total -= parseInt(total_vendor_qty);

                        const idx1 = this.cart.indexOf(data)
                        this.cart_total -= data.products.length
                        this.cart.splice(idx1, 1)
                        // this.cartList()
                        this.generateVendor();
                        this.$emit('updateCarts' , response.data.data.count_cart)
                    }
                });
            },
            mark_checkout(index1, index, event){
                let status = false;
                var qty = this.cart[index1].products[index].quantity;
                if (event.target.checked) {
                    status = true;
                    this.checked_item.push(this.cart[index1].products[index])
                    this.checkout_total += parseInt(qty);
                    // if (window.innerWidth <= 540){
                    //     $('#cart-item'+index1+'-'+index).addClass("checked");
                    // }
                } else {
                    const idx = this.checked_item.indexOf(this.cart[index1].products[index])
                    this.checkout_total -= parseInt(qty);
                    this.checked_item.splice(idx, 1)
                    $('.del-allCart').css('display', 'none');
                    // if (window.innerWidth <= 540){
                    //     $('#cart-item'+index1+'-'+index).removeClass("checked");
                    // }
                }
                CustomerAPi.markCheckout({ pdid : this.cart[index1].products[index].product_details_id , status : status , is_all : this.incheck_All }).then( response => {
                    if(response.data.data > 0){
                        $('#store1-'+index1).prop('checked', true);
                        this.cart[index1].vendor_checkout = true
                    } else {
                        this.cart[index1].vendor_checkout = false
                        $('#store1-'+index1).prop('checked', false);
                    }
                });
            },
            // mark_checkout(index1, index, event){
            //     let status = false;
            //     if (event.target.checked) {
            //         status = true;
            //         this.checked_item.push(this.cart[index1].products[index])
            //         this.checkout_total++;
            //         // if (window.innerWidth <= 540){
            //         //     $('.cart-item').addClass("checked");
            //         // }
            //     } else {
            //         // if (window.innerWidth >= 540){
            //         //     $('.cart-item').removeClass("checked");
            //         // }
            //         const idx = this.checked_item.indexOf(this.cart[index1].products[index])
            //         this.checkout_total--;
            //         this.checked_item.splice(idx, 1)
            //     }
            //     CustomerAPi.markCheckout({ pdid : this.cart[index1].products[index].product_details_id , status : status , is_all : this.incheck_All }).then( response => {
            //     });
            // },
            decQuantity(index1, index){
                const idx =  this.checked_item.indexOf(this.cart[index1].products[index]);
                
                if(this.cart[index1].products[index].quantity > 1){
                    CustomerAPi.cart({ pdid : this.cart[index1].products[index].product_details_id , qty : (this.cart[index1].products[index].quantity - 1) , cusQty : true }).then( response => {
                       this.cart[index1].products[index].quantity--;
                       if(idx >= 0){
                           this.checkout_total--;
                       }
                    });
                }
            },
            addQuantity(index1, index){
                const idx =  this.checked_item.indexOf(this.cart[index1].products[index]);

                if((this.cart[index1].products[index].product_detail.stock) < (this.cart[index1].products[index].quantity + 1)){
                    return Message.alert("Stok produk tidak mencukupi" , "Informasi" , 3000)
                }
                
                CustomerAPi.cart({ pdid : this.cart[index1].products[index].product_details_id , qty : (this.cart[index1].products[index].quantity + 1) , cusQty : true }).then( response => {
                    this.cart[index1].products[index].quantity++;
                    if(idx >= 0){
                        this.checkout_total++;
                    }
                });
            },
            updateQuantity(event, index1, index){
                const qty = $(`#qty-${index1}-${index}`).val();
                if((this.cart[index1].products[index].product_detail.stock) < qty){
                    return Message.alert("Stok produk tidak mencukupi" , "Informasi" , 3000)
                }

                // decreace with previous quantity
                const check = this.checked_item.includes(this.cart[index1].products[index])
                if(check){
                    this.checkout_total -= this.cart[index1].products[index].quantity;
                }
                
                CustomerAPi.cart({ pdid : this.cart[index1].products[index].product_details_id , qty : qty , cusQty : true }).then( response => {
                    this.cart[index1].products[index].quantity = qty;
                    if(check){
                        this.checkout_total += parseInt(qty);
                    }
                });
            },
            show_modal_confirm(status_deleted){
                if(status_deleted == 'item'){
                    this.deleteAll = false;
                    if(this.checked_item.length < 1){
                        Message.alert('Pilih minimal 1 item' , 'Informasi' , 1500);
                        return; //alert('Pilih minimal 1 item');
                    }
                }else{
                    this.deleteAll = true;
                }
                $("#confirmation_delete_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            checkAll(){
                if(this.incheck_All === true){
                    this.checked_item = []
                    this.incheck_All = false
                    this.checkout_total = 0;
                    $('.del-allCart').css('display', 'none');
                    $(':checkbox').prop('checked', false);
                    for (const [i, value] in this.cart) {
                        this.cart[i].vendor_checkout = false
                    }
                }else {
                    this.incheck_All = true
                    for (const [i, value] in this.cart) {
                        for (const j in this.cart[i].products) {
                            const check = this.checked_item.includes(this.cart[i].products[j])
                            if(!check){
                                this.checked_item.push(this.cart[i].products[j])
                                var qty = this.cart[i].products[j].quantity;
                                this.checkout_total += parseInt(qty);
                            }
                        }
                        this.cart[i].vendor_checkout = true
                    }
                    $('.del-allCart').css('display', 'flex');
                    $(':checkbox').prop('checked', true);
                }
                CustomerAPi.markCheckout({ pdid : false , status : false , is_all : this.incheck_All  }).then( response => {
                });
            },
            vendorCheck(vendor, idx, event){
                if(event.target.checked){
                    this.cart[idx].vendor_checkout = true
                    for (const j in this.cart[idx].products) {
                        let qty = this.cart[idx].products[j].quantity;
                        const check = this.checked_item.includes(this.cart[idx].products[j])
                        if(!check){
                            this.checked_item.push(this.cart[idx].products[j])
                        }
                        this.checkout_total += parseInt(qty); 
                    }
                    this.vendorChecked = true;
                    $('.delStor-'+idx).css('display', 'flex');
                    $('.check-prod-'+idx).prop('checked', true);
                } else {
                    this.incheck_All = false
                    for (const j in this.cart[idx].products) {
                        const check = this.checked_item.includes(this.cart[idx].products[j])
                        let dec_qty = this.cart[idx].products[j].quantity;
                        if(check){
                            const index = this.checked_item.indexOf(this.cart[idx].products[j])
                            this.checked_item.splice(index, 1)
                        }
                        this.checkout_total -= parseInt(dec_qty);
                    }
                    this.vendorChecked = false;
                    $('.delStor-'+idx).css('display', 'none');
                    $('.check-prod-'+idx).prop('checked', false);
                }

                CustomerAPi.markCheckout({ pdid : false , status : false , is_all : this.vendorChecked, vendor_id : vendor  }).then( response => {

                });
            },
            updateTheCart(){
                if(this.deleteAll == true){
                    this.checked_item = [];
                    this.cart_list.cart = [];
                }else{
                    let deleted = this.checked_item
                    let filteredArray = this.cart_list.cart.filter(item => !deleted.includes(item));
                    this.cart_list.cart = filteredArray;
                    this.checked_item = [];
                }

            },
            CheckoutOrder(){
                if(this.checked_item.length > 0){
                    this.$router.push({path : 'checkout'})
                }else{
                     Message.alert('Pilih Barang yang akan dibeli , atau coba belanja lagi' , 'Informasi' , 1500);
                }
            },
            manageCart($event){
                $('.cart-manage').toggleClass('cancel')
                let check = $( "#cart-content" ).hasClass("cancel")
                if(check){
                    $('.cart-checkAll').css('display', 'flex')
                    $('.hd-checklist.store').css('display', 'block')
                } else{
                    $('.cart-checkAll').css('display', 'none')
                    $('.hd-checklist.store').css('display', 'none')
                }
                
            },
        },
         computed : {
            TotalPayment : function(){
               return this.checked_item.reduce((Tprice, n) => {
                    return Tprice += parseInt(n.product_detail.face_price) * parseInt(n.quantity);
               }, 0)
            }

        },
        components : {
            AddItem,
            ModalDeleteProduct,
            BlankPage,
            Message,
            WishComponent,
        }
    }
</script>
<style scoped>
</style>
