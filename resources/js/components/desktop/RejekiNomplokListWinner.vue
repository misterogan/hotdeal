<template>
    <div class="hd-rejeki-nomplok">
        <div id="list_winner_rejeki_page">
            <img class="w-50 m-auto d-flex mbottom-20" src="img/logo_rejeki_nomplok.svg" alt="">
            <div class="row-hd period-active align-center justify-center mbottom-10">
                <h5 class="fw-400 fs-11">Pemenang Periode {{week.week}} | <span class="fw-600 fs-11">{{week.periode}}</span></h5>
            </div>
            <div class="row-hd modal-rejeki-top">
                <div class="col-7">
                    <div class="row-hd justify-center">
                        <img width="38" class="big-icon mright-5" src="/img/ic_rejeki_big.svg" alt="">
                        <div class="d-block">
                            <h5 class="fp-lightpurple">Gift Hot Point</h5>
                            <h2 class="custom__nunito">{{week.total_point | NumberFormat}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="row-hd flex-column align-center">
                        <div class="d-flex align-center justify-center">
                            <h3 class="fp-lightpurple fw-700 mright-5">IHSG</h3>
                            <img class="ic_guide" src="/img/ic_guide.svg" alt="">
                        </div>
                        <h2 class="custom__nunito">{{week.first_ihsg}}<span class="unique">{{week.last_ihsg}}</span></h2>
                    </div>
                </div>
            </div>
            <div class="row-hd flex-column modal-tabel-rejeki">
                <div class="row-hd tabel-top __custom-page">
                    <div class="col-6">ID Pemenang</div>
                    <div class="col-6">Barang Pembelian</div>
                </div>
                <div class="row-hd flex-column tabel-body" v-if="Object.keys(winners).length > 0">
                    <div class="row-hd rejeki-winner-row __custom-page" v-for="(item , index) in winners" :key="index">
                        <div class="col-6 row-hd align-start flex-column">
                            <div class="fw-600">{{item.user.name}}</div>
                            <div class="fw-400">Rejeki Nomplok - {{item.id}}</div>
                        </div>
                        <div class="col-6 product row-hd flex-column">
                            <span class="fp-purple fw-700 text-left">{{item.product.name}}</span>
                            <button class="btn-secondary __custom-rejeki">
                                <router-link :to="'/product-detail/' + item.product.slug">Lihat</router-link>
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <blank-page :message="'Tidak ada pemenang untuk periode ini.'" :image="'img/animation_empty_coupon.svg'"></blank-page>
                </div>
                <div class="row-hd disclaimer-bottom" v-if="Object.keys(winners).length > 0">
                    Hadiah ditambahkan ke Hot Point masing-masing pemenang
                </div>
            </div>
            <button id="floating_btn" class="btn-link __custom-rejeki" v-if="btn_more" @click="loadMore">
                <span>Selanjutnya</span>
                <img src="img/arrow_next.svg" alt="">
            </button>
        </div>
    </div>
</template>

<script>
    import apiCustomer from '../../apis/Customer'
    import BlankPage from './BlankPage.vue'


    export default {
        name: "RejekiNomplokListWinner.vue",
        data() {
            return {
                week: {},
                winners: {},
                page: 1,
                btn_more: true
            }
        },
        methods: { 
            close_modal(){
                this.$emit('change',{});
            },
            show_winner_list(){
                apiCustomer.winner({week:this.$route.query.week, page:this.page}).then( response => {
                    this.week = response.data.data.week
                    if(this.page == 1){
                        this.winners = response.data.data.winner.data
                    }else{
                        this.winners.push.apply( this.winners ,response.data.data.winner.data)
                        $("#modal_load").fadeOut();
                    }
                    if(Object.keys(response.data.data.winner.data).length < 2){
                        this.btn_more = false
                    }
                })
            },
            
            loadMore(){
                $("#modal_load").fadeIn();
                this.page++;
                this.show_winner_list();
            },
        },
        mounted(){
            this.show_winner_list()
        },
        components:{
            BlankPage
        }
    }
</script>

<style>

</style>
