<template>
    <div>
        <div class="rejeki-nomplok">
            <div class="detail-list-pemenang">
                <div class="row-100">
                    <div class="container-logo">
                        <img src="/img/logo_rejeki_nomplok.svg" alt="" class="logo">
                    </div>
                </div>
                <div class="row-100" v-if="Object.keys(week).length > 0">
                    <div class="numbers">
                        <p>penutupan IHSG : {{week.ihsg}}</p>
                    </div>
                </div>
                <div class="row-100" v-if="Object.keys(week).length > 0">
                    <div class="text-pemenang">
                        <p><strong>pemenang periode {{week.week}}</strong> | {{week.start_date}} sampai {{week.end_date}} </p>
                    </div>
                </div>
                <!-- <div class="row-100">   
                    <div class="produk-periode">
                        <div class="col-4">
                            <h5>power bank xiaomi</h5>
                        </div>
                        <div class="col-4">
                            <h5>Rp  120.000</h5>
                        </div>
                        <div class="col-4">
                            <h5>lihat produk</h5>
                        </div>
                    </div>
                </div> -->
                <div class="row-100" v-if="Object.keys(winners).length > 0 ">
                    <div class="winner-list">
                        <div class="head-list">
                            <div class="data">
                                <div class="col-4">
                                    <h5>ID pemenang</h5>
                                </div>
                                <div class="col-4">
                                    <h5>nama pemenang</h5>
                                </div>
                                <div class="col-4">
                                    <h5>barang pembelian</h5>
                                </div>
                            </div>
                        </div>
                        <div class="body-list" v-if="Object.keys(winners).length > 0 ">
                            <div class="data" v-for="(item , index) in winners" :key="index">
                                <div class="col-4">
                                    <h6>{{item.id}}</h6>
                                </div>
                                <div class="col-4">
                                    <h6>{{item.user.name}}</h6>
                                </div>
                                <div class="col-4">
                                    <h6>{{item.product.name}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <blank-page :message="'Tidak ada pemenang untuk periode ini.'" :image="'img/animation_empty_coupon.svg'"></blank-page>
                </div>
                <div class="row-100" v-if="Object.keys(winners).length > 0 ">
                    <div class="info">
                        <h6>hadiah ditambahkan ke hot point masing masing pemenang</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script> 
    import apiCustomer from '../../apis/Customer'
    import BlankPage from './BlankPage.vue'

    export default {
        name: "RejekiNomplokPemenang.vue",
        data(){
            return{
                winners : {},
                week : {}
            }
        },
        components : {
            BlankPage
        },
        mounted(){
            this.winner()
        },
        methods: {
            winner(){
                apiCustomer.winner({week:this.$route.query.week}).then( response => {
                    this.week = response.data.data.week
                    this.winners = response.data.data.winner
                });
            }
        },
    }
</script>

<style>

</style>