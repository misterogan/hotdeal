<template>
    <div>
        <div class="row col2-flex">
            <div class="col-20p">
                <div class="mbottom-10 ph-desktop" v-if="is_loading">
                    <div class="ph-row">
                        <div class="ph-col-12 ph-h100 bg-placeholder mright-10"></div>
                    </div>
                </div>
                <div v-if="!is_loading">
                    <button class="trigger" id="floating_btn">
                        <img src="/img/icon_filter_white.svg" alt="">
                        <span>filter</span>
                    </button>
                    <div class="slider-filter">
                        <div class="close">
                            <img class="btn-bar" src="img/ic_bar_white.svg" alt="">
                            <img class="btn-close" src="img/ic_close_filter.svg" alt="">
                        </div>
                        <location :location_data="location_data" @updateFilter="doFilter($event)"></location>
                        <div class="leftside-menu mbottom-20">
                            <h4>Range Harga</h4>
                            <div class="price-slider-web">
                                <div class="price-input">
                                    <input type="number" :min="range_min" max="120000" v-model="filter.min_price"/>
                                    <input type="number" :min="filter.min_price" max="120000" v-model="filter.max_price" class="text-right"/>
                                </div>
                                <div class="range-input">
                                    <input :min="range_min" :max="filter.max_price" step="500" type="range" v-model="filter.min_price"/>
                                    <input :min="filter.min_price" :max="range_max" step="500" type="range" v-model="filter.max_price"/>
                                </div>
                            </div>
                            <button class="btn-cta" @click="doFilter({type: 'range'})">
                                Terapkan Filter
                            </button>
                        </div>
                        <button class="btn-secondary" @click="resetFilter">
                            Hapus Filter
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-80p">
                <div class="mbottom-30" v-if="is_loading || is_searching">
                    <div class="ph-desktop">
                        <div class="row-100 justify-between mleft-10">
                            <skeleton-product-with-detail :classes="'4'" v-for="(skeleton , i ) in 5 " :key="i"></skeleton-product-with-detail>
                        </div>
                    </div>
                    <div class="ph-mobile mtop-20">
                        <div class="row-100 justify-between mleft-5">
                            <skeleton-product-with-detail :classes="'6'" v-for="(skeleton , i ) in 2 " :key="i"></skeleton-product-with-detail>
                        </div>
                        <div class="row-100 justify-between mleft-5">
                            <skeleton-product-with-detail :classes="'6'" v-for="(skeleton , i ) in 2 " :key="i"></skeleton-product-with-detail>
                        </div>
                    </div>
                </div>
                <div class="sticky-product" v-if="!is_loading">
                    <div class="row-100">
                        <h5 style="padding: 0 0 10px 5px;color:#44454f;">Produk Bundling </h5>
                    </div>
                    <div class="row-100">
                        <ul class="search-selected-filter" v-if="filter.selected_filter">
                            <li v-for="(item, index) in filter.selected_filter" :key="index">{{item.name}}<span class="close" v-on:click="removeFilter(index)">x</span></li>
                        </ul>
                    </div>
                    <div v-if="!is_searching">
                        <div class="row-100 product-list" v-if="Object.keys(PList).length > 0">
                            <small-card-product-without-stock :item="item" v-for="(item , index) in PList"  :key="index" @updateCarts="$emit('updateCarts' , $event)"></small-card-product-without-stock>
                        </div>
                        <div v-else>
                            <blank-page :message="'Tidak Menemukan Barang Yang Kamu Cari'" :image="'/img/animation_empty_search.svg'"></blank-page>
                        </div>
                        <div class="row-100 justify-right">
                            <ul class="pagination"  v-if="total_page > 1">
                                <li  v-for="i in total_page" :key="i"  @click="Pagination(i)" :class=" i == current_page ? 'active' :''">{{i}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.nav-search {
    /*display: none;*/
}
</style>

<script>
    import apiCustomer from '../../apis/Customer'
    import SkeletonProductWithDetail from '../loading/SkeletonProductWithDetail.vue';
    import Category from "./component/filter/Category";
    import Location from './component/filter/Location.vue';
    import HighLightImageOnly from './component/product/HighLightImageOnly.vue';
    import HighlightVideoProduct from './component/product/HighlightVideoProduct.vue';
    import SmallCardProductWithoutStock from './component/product/SmallCardProductWithoutStock.vue'
    import BlankPage from "../desktop/BlankPage.vue"

    export default {
        components: {
            Category,
            HighLightImageOnly,
            HighlightVideoProduct,
            SkeletonProductWithDetail,
            SmallCardProductWithoutStock,
            Location,
            BlankPage
        },
        name: "AllProduct.vue",
        data: () => ({
            PList : {},
            filter : {
                province_id : null,
                min_price : 0,
                max_price : 0,
                category_id : 0,
                review : null,
                brand_id : null,
                keyword : '',
                selected_filter : [],
            },
            is_loading : true,
            keyword : '',
            page : 1,
            current_page : 1,
            total_page : 1,
            location_data : {},
            category_data : {},
            range_min : 0,
            range_max : 0,
            is_searching : false
        }),
        computed: {

        },
        mounted(){
            this.ProductSearch();
            this.initializeSliderWeb();
            this.initializeSliderMobile();
        },
        methods: {
            ProductSearch(){
                this.is_loading = true;
                this.keyword = this.$route.query.search
                this.page = this.$route.query.page;
                this.filter.keyword = this.keyword;
                this.search();
            },
            doFilter(event){
                this.is_loading = true;
                this.PList = {}
                this.is_searching = true;

                if(event.type === 'location') {
                    if(this.check_filter_index(event.data.province_name) === -1){
                        this.filter.selected_filter.push({
                            type: event.type,
                            id : event.data.province_id,
                            name : event.data.province_name
                        })
                    }
                    this.filter.province_id = event.province_id
                }
                if(event.type === 'category') {
                    let category_index = this.check_filter_index(event.data.category_name);
                    if(category_index === -1) {
                        this.filter.selected_filter.push({
                            type: event.type,
                            id: event.data.id,
                            name: event.data.category_name
                        })
                    }else{
                        this.filter.selected_filter.splice(category_index, 1);
                    }
                    $("html, body").animate({ scrollTop: 0 },500);
                }
                if (event.type === 'range') {
                    let range_index = this.check_filter_index('Harga');
                    if(range_index === -1) {
                        this.filter.selected_filter.push({
                            type: event.type,
                            id: 0,
                            name: 'Harga',
                            min_price: this.min_price,
                            max_price: this.max_price
                        })
                    }else{
                        this.filter.selected_filter[range_index]['min_price'] = this.min_price;
                        this.filter.selected_filter[range_index]['max_price'] = this.max_price;
                    }
                }
                $(".btn-close").click();
                apiCustomer.filterProductBundling({filter: this.filter}).then(response => {
                    if(response.data.code === 200){
                        //this.PList = response.data.data.product;
                        this.is_searching = false;

                        this.PList = response.data.data.product;
                        this.is_loading = false;
                        this.total_page = response.data.data.total_page
                        this.current_page = response.data.data.current_page
                        this.total_record = response.data.data.total_record
                        //this.location_data = response.data.data.location
                        //this.category_data = response.data.data.category
                        //this.range_min = response.data.data.min
                        //this.range_max = response.data.data.max
                        //this.filter.max_price = response.data.data.max
                        //this.filter.min_price =  response.data.data.min
                        //this.filter.selected_filter = [];
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                })
            },
            removeFilter(index){
                this.is_searching = true;
                this.is_loading = true
                $("#check"+this.filter.selected_filter[index]['id']).prop('checked', false);
                this.filter.selected_filter.splice(index, 1);
                apiCustomer.filterProductBundling({filter: this.filter}).then(response => {
                    if(response.data.code === 200){
                        this.is_searching = false;
                        this.PList = response.data.data.product;
                        this.is_loading = false;
                        this.total_page = response.data.data.total_page
                        this.current_page = response.data.data.current_page
                        this.total_record = response.data.data.total_record
                    }
                })

            },
            resetFilter() {
                this.PList = {}
                this.is_searching = true;
                this.filter = {
                    province_id : null,
                    min_price : 0,
                    max_price : 0,
                    category_id : 0,
                    review : null,
                    brand_id : null
                }
                this.filter.selected_filter = [];
                apiCustomer.filterProductBundling({filter: this.filter}).then(response => {
                    if(response.data.code === 200){
                        this.is_searching = false;
                        this.PList = response.data.data.product;
                        this.total_page = response.data.data.total_page
                        this.current_page = response.data.data.current_page
                        this.total_record = response.data.data.total_record
                    }
                })
            },
            check_filter_index(keyword){
                if(this.filter.selected_filter.length > 0){
                    return this.filter.selected_filter.map(function(e) { return e.name.toLowerCase(); }).indexOf(keyword.toLowerCase());
                }
                return -1;
            },
            category_check(e){
            },
            show_modal_video(){
                $("#video_produk_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            Pagination(page){
                this.$router.replace({path:'/bundling/product', query : {page : page , search : this.keyword}})
                this.page =  page
                this.search();
            },
            initializeSliderWeb() {
                var parent = document.querySelector(".price-slider-web");
                if(!parent) return;

                var
                    rangeSweb = parent.querySelectorAll(".range-web"),
                    numberSweb = parent.querySelectorAll(".number-web");

                rangeSweb.forEach(function(el) {
                    el.oninput = function() {
                        var slide1 = parseFloat(rangeSweb[0].value),
                            slide2 = parseFloat(rangeSweb[1].value);

                        if (slide1 > slide2) {
                            [slide1, slide2] = [slide2, slide1];
                        }

                        numberSweb[0].value = slide1;
                        numberSweb[1].value = slide2;
                    }
                });

                numberSweb.forEach(function(el) {
                    el.oninput = function() {
                        var number1 = parseFloat(numberSweb[0].value),
                            number2 = parseFloat(numberSweb[1].value);

                        if (number1 > number2) {
                            var tmp = number1;
                            numberSweb[0].value = number2;
                            numberSweb[1].value = tmp;
                        }

                        rangeSweb[0].value = number1;
                        rangeSweb[1].value = number2;
                    }
                });
            },
            initializeSliderMobile() {
                var parent = document.querySelector(".price-slider-mobile");
                if(!parent) return;

                var
                    rangeSmobile = parent.querySelectorAll(".range-mobile"),
                    numberSmobile = parent.querySelectorAll(".number-mobile");

                rangeSmobile.forEach(function(el) {
                    el.oninput = function() {
                        var slide1 = parseFloat(rangeSmobile[0].value),
                            slide2 = parseFloat(rangeSmobile[1].value);

                        if (slide1 > slide2) {
                            [slide1, slide2] = [slide2, slide1];
                        }

                        numberSmobile[0].value = slide1;
                        numberSmobile[1].value = slide2;
                    }
                });

                numberSmobile.forEach(function(el) {
                    el.oninput = function() {
                        var number1 = parseFloat(numberSmobile[0].value),
                            number2 = parseFloat(numberSmobile[1].value);

                        if (number1 > number2) {
                            var tmp = number1;
                            numberSmobile[0].value = number2;
                            numberSmobile[1].value = tmp;
                        }

                        rangeSmobile[0].value = number1;
                        rangeSmobile[1].value = number2;
                    }
                });
            },
            search(){
                apiCustomer.searchProductBundling({keyword : this.keyword, page : this.page}).then(response => {
                    this.PList = response.data.data.product;
                    this.is_loading = false;
                    this.total_page = response.data.data.total_page
                    this.current_page = response.data.data.current_page
                    this.total_record = response.data.data.total_record
                    this.location_data = response.data.data.location
                    this.category_data = response.data.data.category
                    this.range_min = response.data.data.min
                    this.range_max = response.data.data.max
                    this.filter.max_price = response.data.data.max
                    this.filter.min_price =  response.data.data.min
                    this.filter.selected_filter = [];
                })
            }
        },
        created() {
            this.$watch(
                () => this.$route.query,
                (toParams, previousParams) => {
                    if(this.keyword !== toParams.search){
                        this.keyword = toParams.search;
                        this.is_loading = true;
                        this.page = 1
                        this.filter.keyword = this.keyword;
                        this.search();
                    }
                }
            )
        }
    }
</script>
