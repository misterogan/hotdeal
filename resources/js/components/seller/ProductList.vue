<template>
    <div>
        <div class="main-content">
            <div class="center-menu" style="width:100%">
                <div class="main-menu">
                    <div class="box product-list">
                        <h3>daftar produk</h3>
                        <div class="row-100">
                            <div class="top-list">
                                <router-link to="/vendor/product/create">
                                <button class="btn-secondary"   >
                                    <img src="/img/ic_plus_vendor.svg" alt="">
                                    tambah produk
                                </button>
                                </router-link>
                            </div>
                        </div>
                        <div class="row-100 second-top">
                            <div class="col search">
                                <div class="container-search">
                                    <form v-on:submit.prevent="ProductList">
                                    <input v-model="pagination.search" @keyup="ProductList()" name="search" class="input-search" type="search" autocomplete="off"  placeholder="Masukan nama produk atau SKU" />
                                    <img class="ic_search" src="/img/ic_search.svg" alt="">
                                        </form>
                                </div>
                            </div>
                            <div class="col select filter">
                                <h5>kategori</h5>
                                <select tabindex="1" class="select-filter" v-on:change="sortCategory($event.target.value)">
                                    <option disabled value="">Please select one</option>
                                    <option v-for="(item, index) in category " :key="index" :value="item.id">{{item.category}}</option>
                                </select>
                            </div>
                            <div class="col select filter">
                                <h5>status</h5>
                                <select class="select-filter" tabindex="1">
                                    <option>selesai</option>
                                    <option>dibatalkan</option>
                                    <option>belum bayar</option>
                                    <option>perlu dikirim</option>
                                    <option>proses dikirim</option>
                                </select>
                            </div>
                        </div>
                        <div class="row-100">
                            <div class="list-product">
                                <table class="product-table">
                                    <thead>
                                        <tr>
                                            <th>info produk</th>
                                            <th>
                                                <div class="menu-icon">
                                                    harga
                                                    <img src="/img/ic_sort.svg" alt="">
                                                </div>
                                            </th>
                                            <th>
                                                <div class="menu-icon">
                                                    stok
                                                    <img src="/img/ic_sort.svg" alt="">
                                                </div>
                                            </th>
                                            <th>aktif</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div v-if="is_loading">
                                    <div class="ph-row justify-between mtop-30">
                                        <div class="ph-col-4s mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                        <div class="ph-col-1 mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                        <div class="ph-col-1 mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                        <div class="ph-col-1 mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                        <div class="ph-col-1 mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                    </div>
                                    <div class="ph-row justify-between">
                                        <div class="ph-col-4s mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                        <div class="ph-col-1 mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                        <div class="ph-col-1 mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                        <div class="ph-col-1 mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                        <div class="ph-col-1 mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                    </div>
                                    <div class="ph-row justify-between">
                                        <div class="ph-col-4s mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                        <div class="ph-col-1 mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                        <div class="ph-col-1 mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                        <div class="ph-col-1 mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                        <div class="ph-col-1 mright-60">
                                            <div class=" bg-placeholder mbottom-30"></div>
                                        </div>
                                    </div>
                                </div>
                                <table v-if="!is_loading" class="product-table">
                                    <tbody v-if="Object.keys(productlist).length">
                                        <tr v-for="(item , index) in productlist" :key="index">
                                            <td>
                                                <div class="order product">
                                                    <div class="container-img" v-if="item.image != null">
                                                        <img :src="item.image" :alt="item.name">
                                                    </div>
                                                    <div class="container-img" v-else>
                                                    </div>
                                                    <div class="info">
                                                        <h6>{{item.name}}</h6>
                                                        <h6> SKU : <b>{{item.sku}}</b></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td >
                                                <table class="child-table">
                                                    <tbody>
                                                    <tr v-for="(item2 , index2) in item.details" :key="index2">
                                                        <td >{{item2.price}}</td>
                                                        <td >{{item2.stock}}</td>
                                                        <td >
                                                            <!-- <form v-on:submit.prevent="updateStatus()"> -->
                                                            <button class="check" type="submit" @change="updateStatus(item2)">
                                                                <input type="hidden" :value="item2.id">
                                                                <input :id="'active'+index+'-'+index2" class="check-status" type="checkbox" name="status" checked v-if="item.status == 'active'"/>
                                                                <input :id="'active'+index+'-'+index2" class="check-status" type="checkbox" name="status" v-else disabled>
                                                                <label :for="'active'+index+'-'+index2"></label>
                                                            </button>
                                                            <!-- </form> -->
                                                        </td>
                                                        <td>
                                                            <div class="img-wrapper">
                                                                <router-link :to="'/vendor/product/edit/'+item.slug">
                                                                    <img src="/img/ic_details.svg" alt="">
                                                                </router-link>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <blank-page class="mtop-30" :message="'Produk Tidak Ditemukan'" :image="'/img/animation_empty_order.svg'"></blank-page>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row-100 mtop-50">
                            <pagination :pagination="pagination" @updatePage="updatePage($event)"></pagination>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style >
</style>
 
<script>
    import Vue from 'vue'
    import Vuelidate from 'vuelidate'
    import apiVendor from '../../apis/Vendor'
    import BlankPage from '../desktop/BlankPage.vue'
    import Pagination from '../pagination/pagination.vue'
    Vue.use(Vuelidate)
    export default {
        data(){
            return {
                form : {
                    status : '',
                    id : '',
                    vendor_id : '',
                },
                pagination : {
                    page : 1,
                    current : 1,
                    total : 1,
                    status : '',
                    date : '',
                    search : '',
                    filter : '',
                    perpage : 10,
                    category : ''
                },
                productlist : {},
                submitButton : false,
                id : '',
                is_loading : false,
                category : {},
                selected_category : null,
                
            }
        },
        mounted(){
            this.ProductList();
        },
        methods :{
            ProductList(){
                this.timer = setTimeout(() => {
                    apiVendor.producList(this.pagination).then( response => {
                        this.productlist = response.data.data.products
                        this.category = response.data.data.category
                        this.pagination.total = response.data.data.total
                        this.pagination.current = response.data.data.current_page
                        this.is_loading = false
                    })
                }, 500);
            },
            sortCategory: function(value) {
                this.pagination.category = value
                this.ProductList()
            },
            updatePage(e){
                if(e.type == 'page'){
                    this.pagination.page = e.value
                    if(this.pagination.total < e.value){
                        return ;
                    }
                }
                if(e.type == 'category'){
                    this.pagination.category = e.value
                    this.pagination.page = 1
                }
                this.ProductList()
            },
            updateStatus(i){
                this.id = i.id.toString();
                this.showError = false
                    this.submitButton = true;
                if(this.submitButton == true){
                    const fd = new FormData();
                    fd.append('id', this.id);
                    fd.append('product_id', i.product_id);
                    if (i.status == 'active') {
                        fd.append('status', 'inactive');
                    } else {
                        fd.append('status', 'active');
                    }
                    apiVendor.updateStatus(fd).then( response => {
                        this.submitButton = true;
                        if(response.data.code == 200){
                            window.location.reload()
                        }else{
                            alert('Error saat menyimpan data');
                        }
                    });
                    
                }
            },
        },
        components : {
            BlankPage,
            Pagination
        }
    }
</script>
