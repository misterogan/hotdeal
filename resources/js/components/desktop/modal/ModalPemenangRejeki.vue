<template>
    <div id="list_winner_rejeki" class="modal">
        <div class="modal-dialog w-700">
            <div class="modal-body">
                <span class="close-modal" @click="close_modal"></span>
                <div class="content-modal">
                    <h5 class="modal-title">Pemenang Rejeki Nomplok Periode Ini</h5>
                    <div class="row-hd modal-rejeki-top">
                        <div class="col-5">
                            <div class="row-hd flex-column align-left">
                                <div class="row-hd align-center justify-left">
                                    <h2 class="mright-5">Hot Point</h2>
                                    <img class="big-icon mright-5" src="/img/ic_rejeki_big.svg" alt="">
                                    <h2 class="mright-5 custom__nunito">{{data.total_point | NumberFormat}}</h2>
                                    <img class="ic_guide mright-5" src="/img/ic_guide.svg" alt="">
                                </div>
                                <p>Pemenang Hot Point Terbanyak</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="row-hd flex-column align-left">
                                <div class="row-hd align-center justify-left">
                                    <h2 class="mright-5">IHSG</h2>
                                    <h2 class="mright-5 custom__nunito">{{data.first_ihsg}}<span class="unique">{{data.last_ihsg}}</span></h2>
                                    <img class="ic_guide mright-5" src="/img/ic_guide.svg" alt="">
                                </div>
                                <p>Nomor Kupon Pemenang: <span class="unique">{{data.last_ihsg}}</span></p>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row-hd flex-column align-left">
                                <h5 class="fw-600">Periode {{data.week}}</h5>
                                <h5 class="fw-300">{{data.periode}}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row-hd flex-column modal-tabel-rejeki">
                        <div class="row-hd tabel-top">
                            <div class="col-4">ID Pemenang</div>
                            <div class="col-4">Nama Pemenang</div>
                            <div class="col-4">Barang Pembelian</div>
                        </div>
                        <div class="row-hd flex-column tabel-body" v-if="Object.keys(winners).length > 0">
                            <div class="row-hd rejeki-winner-row" v-for="(item , index) in winners" :key="index">
                                <div class="col-4 id">Rejeki Nomplok - {{item.id}}</div>
                                <div class="col-4 name">{{item.user.name}}</div>
                                <div class="col-4 product"><span>{{item.product.name}}</span></div>
                            </div>
                        </div>
                        <div v-else>
                            <blank-page :message="'Tidak ada pemenang untuk periode ini.'" :image="'img/animation_empty_coupon.svg'"></blank-page>
                        </div>
                    </div>
                    <pagination :total="pagination.total" :current="pagination.current" @changeAction="changeActionPagination($event)"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BlankPage from './../BlankPage.vue'
    import Pagination from '../../pagination/v2/pagination.vue';

    

    export default {
        name: "ModalPemenangRejeki.vue",
        props : ['data', 'winners', 'pagination'],
        data() {
            return {
                week: ''
            }
        },
        methods: { 
            close_modal(){
                this.$emit('change',{});
            },
            changeActionPagination(event){
                this.week = this.data.week
                 if(event < 1 || event > this.pagination.total){
                    return false;
                 }
                this.$parent.show_winner_list(this.week, event)
                 
            },
        },
        mounted(){
            
        },
        components:{
            BlankPage, Pagination
        }
    }
</script>

<style>

</style>
