<template>
    <div v-if="!flash_expired">
        <div id="flash_sale_section" class="mbottom-30" v-if="is_loading">
            <div class="ph-desktop ph-container">
                <card-skeleton :count="6"></card-skeleton>
            </div>
            <div class="ph-mobile ph-container">
                <card-skeleton :count="2"></card-skeleton>
            </div>
        </div>
        <div class="relative flash-sale-product" v-if="is_flashSale && !is_loading">
            <div class="row">
                <div class="flash-sale mbottom-15">
                    <div class="fsale">
                        <img src="/img/ic_flash.svg" alt="">
                        <h3 class="title-section white-nowrap">Flash Deal</h3>
                    </div>
                    <section class="countdown">
                        <div class="days">
                        {{displayDays}}
                        </div>
                        <div class="hours">
                        {{displayHours}}
                        </div>
                        <img src="/img/semicolon.svg" alt="">
                        <div class="minutes">
                        {{displayMinutes}}
                        </div>
                        <img src="/img/semicolon.svg" alt="">
                        <div class="minutes">
                        {{displaySeconds}}
                        </div>
                    </section>
                    <router-link class="white white-nowrap" to="/flash-sale">Lihat Semua</router-link>
                </div>
            </div>
            <div class="d-flex align-center relative">
                <img class="background-flashsale" src="/img/banner_start_flash.svg" alt="">
                <button @click="slideFunction('right')" class="prev"></button>
                <button @click="slideFunction('left')" class="next"></button>
                <div class="flash-product" v-if=" PList != null" >
                    <div :class="+index===0 ? 'slide-product item relative first-child':'slide-product item relative'" v-for="(item , index) in PList"  :key="index">
                        <SmallCardProduct :item="item" @updateCarts="$emit('updateCarts' , $event)"></SmallCardProduct>                        
                        <div class="badge-flash"></div>
                    </div>
                    <div class="slide-product item relative last-child"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SmallCardProduct from './SmallCardProduct.vue'
    import Customer from '../../../../apis/Customer'
    import CardSkeleton from '../../../skeleton/CardSkeleton.vue'

    export default {
        name: "FlashSale",
        data: () => ({
            displayDays: 0,
            displayHours: 0,
            displayMinutes: 0,
            displaySeconds: 0,
            PList : {},
            is_loading : true,
            year : 0,
            month : 0,
            day : 0,
            hours : 0,
            minute : 0,
            second : 0,
            is_flashSale : false,
            observer: null,
            intersected: false,
            slide_val : '',
            slide_position : {current : 0},
            flash_expired: false,
            end_date : ''
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
            },
        },
        mounted(){
            this.get_product();
        },
        updated(){
            this.scrollFlashProduct();
        },
        methods: {
            scrollFlashProduct(){
                const element = document.querySelector('.flash-product');
                const firstChild = document.querySelector('.first-child');
                const lastChild = document.querySelector('.last-child');
                this.observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting){
                            if (entry.target.classList.contains('first-child')) {
                                $('.prev').hide();
                                $('.next').show();
                            } else{
                                
                            }
                            if (entry.target.classList.contains('last-child')) {
                                $('.next').hide();
                                $('.prev').show();
                            } else{
                                
                            }
                        }
                    })
                });
                this.observer.observe(firstChild);
                this.observer.observe(lastChild);
            },
            slideFunction(val){
                $('.prev').show();
                $('.next').show();
                this.slide_val = val;
                if(this.slide_val == 'left'){
                    this.slide_position.current += 422
                    $('.slide-product').css("transform","translateX(-"+this.slide_position.current+"px)");
                } else if(this.slide_val == 'right'){
                    this.slide_position.current -= 422
                    $('.slide-product').css("transform","translateX(-"+this.slide_position.current+"px)");
                };
            },
            show_alert(){
                $("#alert_modal").fadeIn(function (){
                    $("body").addClass('overflow-hidden')
                })
            },
            show_modal_video(){
                $("#video_produk_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
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
                    if(this.displayHours === '00' && this.displayMinutes === '00' && this.displaySeconds === '00'){
                        this.flash_expired = true;
                    }
                }, 1000);
            },
            get_product(){
              const element = document.querySelector("#flash_sale_section");
              this.observer = new IntersectionObserver(entries => {
                const image = entries[0];

                if (image.isIntersecting) {
                  this.intersected = true;
                  Customer.flashsale().then(response => {
                    // console.log(response.data.data.flash_data.end_date >= now)

                    this.year = response.data.data.flash_data.year
                    this.month = response.data.data.flash_data.month
                    this.day = response.data.data.flash_data.day
                    this.hours = response.data.data.flash_data.hours
                    this.minute = response.data.data.flash_data.minute
                    this.second = response.data.data.flash_data.second
                    this.PList = response.data.data.product
                    this.is_flashSale = response.data.data.is_flashSale
                    this.end_date = response.data.data.flash_data.end_date
                    this.is_loading = false
                    this.showRemaining();
                    this.observer.disconnect();
                  })
                }
              },{rootMargin: "100px 0px 0px 0px"});

              this.observer.observe(element);
            }
        },
        components : {
            SmallCardProduct,
            CardSkeleton
        }
    }
</script>

<style scoped>

</style>
