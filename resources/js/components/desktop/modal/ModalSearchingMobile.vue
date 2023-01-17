<template>
    <div id="modal_search" class="modal">
            <div class="modal-dialog search">
                <div class="modal-body">
                    <div class="content-modal">
                       <form autocomplete="off" v-on:submit.prevent="searchProduct()">
                           <div class="d-flex align-center mbottom-15">
                                <button class="btn-back mright-10">
                                    <img src="/img/ic_back.svg" alt="" width="20" @click="close_search_menu">
                                </button>
                                <div class="relative w-100">
                                    <input id="searching-mobile" ref="email" name="search" placeholder="Mau barang apa? Cari di sini ..." class=" hotdeal-searching search"
                                    v-model="searchValueModal" type="search" autocomplete="off"
                                    v-on:click="searchRecommendation"
                                    @keyup="searchProduct()"
                                    v-on:focusout="focusout"
                                    v-on:keyup.enter = "search"
                                    :search="searchValue == 'undefined' ? '' : searchValue"/>
                                    <img class="ic_search" src="/img/assets_hotdeal_search.svg" alt="">
                                </div>
                           </div>
                            <div class="autocomplete pop-over">
                                <div class="autocomplete-search">
                                    <div v-if="search_not_found" class="error-not-found">
                                        <i class="fw-600">{{searchValue}}</i>
                                        Tidak ditemukan. Gunakan kata kunci yang lain.
                                    </div>
                                    <ul class="mtop-15 mbottom-15" v-if="recommended_keywords">
                                        <li class="mbottom-15" v-for="(item, index) in recommended_keywords" :key="index">
                                            <router-link class="link d-flex align-center justify-between" :to="'/product?search='+item">
                                                <span class="fp-black fw-600">{{ item }}</span>
                                                <img class="pointer" src="/img/ic_search.svg" width="17" alt="">
                                            </router-link>
                                        </li>
                                    </ul>
                                </div>
                                <div class="searching-options" v-if="Object.keys(history_search).length > 0">
                                    <div class="title-search">
                                        Terakhir dicari
                                        <div class="btn-link pointer" @click="deleteHistory('all', index , 'all')">hapus semua</div>
                                    </div>
                                    <ul class="mbottom-15">
                                        <li v-for="(item, index) in history_search" :key="index">
                                            <router-link class="item-name-search link btn-link" :to="'/product?search='+item.keyword">{{ item.keyword }}</router-link>
                                            <img src="/img/ic_trash_blue.svg" @click="deleteHistory(item.keyword , index , 'keyword')" alt="">
                                        </li>
                                    </ul>
                                    <!-- <button class="see-more">lihat lebih banyak</button> -->
                                </div>
                                <div class="searching-options" v-if="search_recommendation.popular_products">
                                    <div class="title-search">
                                        Pencarian Populer
                                        <!-- <div class="btn-link pointer">Refresh</div> -->
                                    </div>
                                    <ul>
                                        <li v-for="(item, index) in search_recommendation.popular_products" :key="index" >
                                            <div class="item-name-search btn-link" @click="gotoProduct(item.products.slug)">
                                                <img :src="item.image.link" alt="">
                                                <div class="w-100">
                                                    <div class="elipsis-1">{{ item.products.name }}</div>
                                                    <div class="description elipsis-2" v-html="item.products.description"></div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <!-- <button class="see-more">lihat lebih banyak</button> -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    import User from '../../../apis/User'
    import apiCustomer from '../../../apis/Customer'
    export default {
        name: "ModalSearchingMobile.vue",
        props : ['search_recommendation' , 'history_search' , 'search_not_found' ,'searchValue','recommended_keywords'],
        data() {
            return {
                searchValueModal : this.searchValue,
                focus : false,
            }
        },
        //props :['product' , 'status_deleted' , 'status'],
        methods: {
            close_modal(){
                $('.modal').fadeOut();
                $("body").addClass('overflow-scroll');
            },
        },
        mounted(){
            
        },
        updated(){
            //this.autoFocus();
        },
        watch: {
            // $route(to, from) {
            //     this.crumbs()
            // }
        },
        created: function () {
            // this.searchValue = this.$route.query.search != undefined ? this.keyword = this.$route.query.search : '' 
            // if(User.is_login()){
            //     this.is_login = true;
            //     this.search_history();
            // }
            // this.categoryPopular();
        },
        methods: {
            // productPopular(){
            //     apiCustomer.popularProduct().then(response => {
            //        this.product_popular = response.data
            //     })
            // },
            // categoryPopular(){
            //     apiCustomer.categoryPopular().then(response => {
            //        this.category_popular = response.data
            //     })
            // },
            // search_history(){
            //     apiCustomer.searchHistory().then( response => {
            //         this.history_search = response.data.data.options;
            //     });
            // },
            focusInput() {
                this.$refs.email.focus();
            },
            deleteHistory(keyword, index , type){
               this.$emit('deleteHistory' , {keyword:keyword , type : type , index : index})
            },
            searchRecommendation() {
                if(!this.focus){
                    this.$emit('searchProduct' , {key : this.searchValue})
                }
            },
            searchProduct(){
                this.$emit('searchProduct' , {key : this.searchValueModal})
            },
            focusout(){
                this.focus = false;
            },
            // setSearchBar(item) {
            //     this.searchValue = item
            // },
            gotoProduct(slug) {
                location.href = '/product-detail/' + slug
                window.reload();
            },
            search() {
                this.$router.push({path:'/product', query :{ search : this.searchValue}}).catch(()=>{});
                $(".hotdeal-searching").blur();
                $('.overlay-primary').click();
                this.close_search_menu()
                
            },
            close_search_menu(){
                $('#modal_search').removeClass('active');
                $('.overlay-primary').removeClass('active');
                $('.autocomplete').removeClass('active');
                $("body").removeClass('overflow-hidden');
            }
        },
    }
</script>

<style>

</style>