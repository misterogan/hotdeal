<template>
    <div>
        <div class="product-list-page flash-sale-page">
            <div class="mbottom-10 ph-desktop ph-container" v-if="is_loading">
                <div class="ph-row">
                    <div class="ph-col-12 ph-h250 bg-placeholder rounded mbottom-15"></div>
                </div>
            </div>
            <div class="row" v-if="!is_loading">
                <div class="banner-flashsale">
                    <div class="container-img">
                        <img :src="banner" alt="">
                        <!-- <img v-if="!isMobile()" :src="banner" alt="">
                        <img v-else :src="banner_mobile" alt=""> -->
                    </div>
                    <div class="flash-sale">
                        <img class="orange" src="/img/ic_flash.svg" alt="" style="width: 30px">
                        <h3 class="txt-flashsale">FLASH SALE</h3>
                        <div>
                            <section class="countdown">
                                <div class="days">
                                    {{displayDays}}
                                </div>
                                <div class="hours">
                                    {{displayHours}}
                                </div>
                                <img src="/img/white_semicolon.svg" alt="">
                                <div class="minutes">
                                    {{displayMinutes}}
                                </div>
                                <img src="/img/white_semicolon.svg" alt="">
                                <div class="seconds">
                                    {{displaySeconds}}
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row col2-flex">
                <div class="col-20p">
                    <div class="mbottom-10 ph-desktop" v-if="is_loading">
                        <div class="ph-row">
                            <div class="ph-col-12 ph-h200 bg-placeholder rounded mright-20"></div>
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
                                        <input type="number" min="0" max="120000" v-model="minp"/>
                                        <input type="number" min="0" max="120000" v-model="maxp" class="text-right"/>
                                    </div>
                                    <div class="range-input">
                                        <input min="0" max="120000" step="500" type="range" v-model="minp"/>
                                        <input min="0" max="120000" step="500" type="range" v-model="maxp"/>
                                    </div>
                                </div>
                                <button class="btn-cta" @click="doFilter({type: 'range'})">
                                    Terapkan Filter
                                </button>
                            </div>
                            <category class="mbottom-20" :category_data="category_data" @updateFilter="doFilter($event)"></category>
                            <div class="filter-spesifikasi" style="display: none;">
                                <div class="content">
                                    <h4>Penilaian</h4>
                                    <button class="spec filter-rate">
                                        <div class="col">
                                            <ul class="review">
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                            </ul>
                                        </div>
                                    </button>
                                    <button class="spec filter-rate">
                                        <div class="col">
                                            <ul class="review">
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                                <li><img src="img/ic_love_blank.svg" alt=""></li>
                                            </ul>
                                        </div>
                                        <div class="col">
                                            ke atas
                                        </div>
                                    </button>
                                    <button class="spec filter-rate">
                                        <div class="col">
                                            <ul class="review">
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                                <li><img src="img/ic_love_blank.svg" alt=""></li>
                                                <li><img src="img/ic_love_blank.svg" alt=""></li>
                                            </ul>
                                        </div>
                                        <div class="col">
                                            ke atas
                                        </div>
                                    </button>
                                    <button class="spec filter-rate">
                                        <div class="col">
                                            <ul class="review">
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                                <li><img src="img/ic_love_blank.svg" alt=""></li>
                                                <li><img src="img/ic_love_blank.svg" alt=""></li>
                                                <li><img src="img/ic_love_blank.svg" alt=""></li>
                                            </ul>
                                        </div>
                                        <div class="col">
                                            ke atas
                                        </div>
                                    </button>
                                    <button class="spec filter-rate">
                                        <div class="col">
                                            <ul class="review">
                                                <li><img src="img/ic_love.svg" alt=""></li>
                                                <li><img src="img/ic_love_blank.svg" alt=""></li>
                                                <li><img src="img/ic_love_blank.svg" alt=""></li>
                                                <li><img src="img/ic_love_blank.svg" alt=""></li>
                                                <li><img src="img/ic_love_blank.svg" alt=""></li>
                                            </ul>
                                        </div>
                                        <div class="col">
                                            ke atas
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <div class="filter-spesifikasi" style="display: none;">
                                <div class="content">
                                    <h4>opsi pengiriman</h4>
                                    <div class="content-filter">
                                        <div class="spec">
                                            <div class="label">
                                                Instant
                                            </div>
                                            <button class="check">
                                                <input type="checkbox" id="check99" />
                                                <label for="check99"></label>
                                            </button>
                                        </div>
                                        <div class="spec">
                                            <div class="label">
                                                Same Day
                                            </div>
                                            <button class="check">
                                                <input type="checkbox" id="check88" />
                                                <label for="check88"></label>
                                            </button>
                                        </div>
                                        <div class="spec">
                                            <div class="label">
                                                Next Day
                                            </div>
                                            <button class="check">
                                                <input type="checkbox" id="check77" />
                                                <label for="check77"></label>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="filter-spesifikasi" style="display: none;">
                                <div class="content">
                                    <h4>merk</h4>
                                    <div class="content-filter">
                                        <div class="spec">
                                            <div class="label">
                                                Merk1
                                            </div>
                                            <button class="check">
                                                <input type="checkbox" id="merk1" />
                                                <label for="merk1"></label>
                                            </button>
                                        </div>
                                        <div class="spec">
                                            <div class="label">
                                                Merk2
                                            </div>
                                            <button class="check">
                                                <input type="checkbox" id="merk2" />
                                                <label for="merk2"></label>
                                            </button>
                                        </div>
                                        <div class="spec">
                                            <div class="label">
                                                Merk3
                                            </div>
                                            <button class="check">
                                                <input type="checkbox" id="merk3" />
                                                <label for="merk3"></label>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn-secondary" @click="resetFilter">
                                Hapus Filter
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-80p">
                    <div class="mbottom-30" v-if="is_loading || is_searching">
                        <!-- <div class="ph-desktop">
                            <div class="row-100 justify-between mleft-10" >
                                <skeleton-product-with-detail :classes="'4'" v-for="(skeleton , i ) in 5 " :key="i"></skeleton-product-with-detail>
                            </div>
                        </div> -->
                        <card-skeleton :count="5"></card-skeleton>
                        <div class="ph-mobile">
                            <div class="row-100 justify-between mleft-5" >
                                <skeleton-product-with-detail :classes="'6'" v-for="(skeleton , i ) in 2 " :key="i"></skeleton-product-with-detail>
                            </div>
                            <div class="row-100 justify-between mleft-5">
                                <skeleton-product-with-detail :classes="'6'" v-for="(skeleton , i ) in 2 " :key="i"></skeleton-product-with-detail>
                            </div>
                        </div>
                    </div>
                    <div v-if="!is_loading">
                        <div class="row-100">
                            <ul class="search-selected-filter" v-if="filter.selected_filter">
                                <li v-for="(item, index) in filter.selected_filter">{{item.name}}<span class="close" v-on:click="removeFilter(index)">x</span></li>
                            </ul>
                        </div>
                        <div v-if="!is_searching">
                            <div class="row-100 product-list " v-if="Object.keys(PList).length > 0">
                                <small-card-product :item="item" v-for="(item , index) in PList"  :key="index" @updateCarts="$emit('updateCarts' , $event)"></small-card-product>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import apiCustomer from '../../apis/Customer'
    import SkeletonProductWithDetail from '../loading/SkeletonProductWithDetail.vue';
    import SmallCardProduct from './component/product/SmallCardProduct.vue';
    import Category from "./component/filter/Category";
    import Location from './component/filter/Location.vue';
    import CardSkeleton from '../skeleton/CardSkeleton.vue'

    export default {
        components: { 
            SkeletonProductWithDetail,
            SmallCardProduct, 
            Category, 
            Location,
            CardSkeleton
        },
        name: "FlashSaleDetail.vue",
        data: () => ({
            displayDays: 0,
            displayHours: 0,
            displayMinutes: 0,
            displaySeconds: 0,
            PList : {},
            is_loading : true,
            is_searching : false,
            banner : '',
            banner_mobile : '',
            filter : {
                province_id : null,
                min_price : 0,
                max_price : 0,
                category_id : 0,
                review : null,
                brand_id : null,
                selected_filter : [],
            },
            year : 0,
            month : 0,
            day : 0,
            hours : 0,
            minute : 0,
            second : 0,
            location_data : {}, 
            category_data : {},
            minp : 0,
            maxp : 120000,
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
        mounted(){
            this.showRemaining();
            this.flashsale();
            this.initializeSliderWeb();
            this.initializeSliderMobile();
        },
        methods: {
            flashsale(){
                apiCustomer.flashsaleAll().then(response => {
                    this.year = response.data.data.flash_data.year
                    this.month = response.data.data.flash_data.month
                    this.day = response.data.data.flash_data.day
                    this.hours = response.data.data.flash_data.hours
                    this.minute = response.data.data.flash_data.minute
                    this.second = response.data.data.flash_data.second
                    this.PList = response.data.data.product
                    this.is_loading = false
                    this.location_data = response.data.data.location
                    this.category_data = response.data.data.category
                    this.banner = response.data.data.flash_data.banner
                    this.banner_mobile = response.data.data.flash_data.banner_mobile
                    this.filter.selected_filter = [];
                    if (this.banner == null) this.banner = '/img/banner_flashsale.svg'
                    this.showRemaining()
                })
            },
            isMobile() {
                if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    return true
                } else {
                    return false
                }
            },

            formatNum : (num) => num < 10 ? '0' + num : num,
            showRemaining(){
                const timer = setInterval(()=> {
                    const now = new Date();
                    const end = new Date(this.year, this.month, this.day, this.hours, this.minute, this.second);
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
            show_modal_video(){
                $("#video_produk_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            doFilter(event){
                this.PList = {}
                this.is_searching = true;

                if(event.type == 'location') {
                    if(this.check_filter_index(event.data.province_name) === -1){
                        this.filter.selected_filter.push({
                            type: event.type,
                            id : event.data.province_id,
                            name : event.data.province_name
                        })
                    }
                }
                if(event.type == 'category') {
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
                if (event.type == 'range') {
                    let range_index = this.check_filter_index('Harga');
                    if(range_index === -1) {
                        this.filter.selected_filter.push({
                            type: event.type,
                            id: 0,
                            name: 'Harga',
                            min_price: this.minp,
                            max_price: this.maxp
                        })
                    }else{
                        this.filter.selected_filter[range_index]['min_price'] = this.minp;
                        this.filter.selected_filter[range_index]['max_price'] = this.maxp;
                    }
                }
                $(".btn-close").click();
                apiCustomer.flashsaleFilter({filter: this.filter}).then(response => {
                    if(response.data.code == 200){
                        this.PList = response.data.data.product;
                        this.is_searching = false;
                        $("html, body").animate({ scrollTop: 0 },500);
                    }
                })
            },
            removeFilter(index){
                $("#check"+this.filter.selected_filter[index]['id']).prop('checked', false);
                this.filter.selected_filter.splice(index, 1);
                apiCustomer.flashsaleFilter({filter: this.filter}).then(response => {
                    if(response.data.code === 200){
                        this.PList = response.data.data.product;
                    }
                })

            },
            resetFilter() {
                this.PList = {}
                this.filter = {
                    province_id : null,
                    min_price : 0,
                    max_price : 0,
                    category_id : 0,
                    review : null,
                    brand_id : null
                }
                this.filter.selected_filter = [];
                apiCustomer.flashsaleFilter({filter: this.filter}).then(response => {
                    if(response.data.code === 200){
                        $(".category-menu").prop('checked', false);
                        this.minp = 0
                        this.maxp = 120000
                        this.PList = response.data.data.product;
                        this.is_loading = false;
                    }
                })
            },
            check_filter_index(keyword){
                if(this.filter.selected_filter.length > 0){
                    return this.filter.selected_filter.map(function(e) { return e.name.toLowerCase(); }).indexOf(keyword.toLowerCase());
                }

                return -1;
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
        }
    }
</script>

<style scoped>

</style>
