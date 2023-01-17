<template>
<div>
    <div id="general_voucher_card_modal" class="modal">
        <div class="modal-dialog w-800">
            <div class="modal-body">
                <span class="close-modal"></span>
                <div class="content-modal">
                    <h4>Voucher</h4>
                    <div class="voucher-card-promo">
                        <div class="col search mbottom-20">
                            <div class="container-search">
                                <form class="relative" v-on:submit.prevent="method(pagination.page = 1)" v-on:keyup="method($event.target.value)">
                                    <input v-model="pagination.search" name="search" class="input-search" type="search" id="search" autocomplete="off" value="" placeholder="Cari Voucher" />
                                    <img class="ic_search" src="/img/ic_search.svg" alt="">
                                </form>
                            </div>
                        </div>
                        <div class="row-voucher" v-if="Object.keys(voucher_list).length > 0">
                            <div class="col-6 col-sm-12" v-for="(item, index) in voucher_list" :key="index">
                                <div class="card-promo">
                                    <div class="container-img">
                                        <img :src="item.image" :alt="item.voucher_name">
                                    </div>
                                    <div class="desc-promo">
                                        <h5>{{item.voucher_name}}</h5>
                                        <h6>
                                            {{item.voucher_decription}}
                                        </h6>                       
                                    </div>
                                    <div class="col-btn">
                                        <h6 class="btn-link" href="javascript:void(0)" @click="show_modal(item)">
                                            Selengkapnya
                                        </h6>
                                        <button class="btn-primary" href="javascript:void(0)" @click="selected_voucher(item)">
                                            Gunakan Voucher
                                        </button>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div v-if="voucher_list == 0">
                            <blank-page :message="'Saat ini belum ada voucher yang tersedia'" :image="'img/animation_empty_promo.svg'"></blank-page>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="voucher_active" class="modal">
        <div class="modal-dialog purple">
            <div class="modal-body">
                <span class="close-modal"></span>
                <div class="content-modal">
                    <div class="confirmation">
                        <div class="row">
                            <h5 style="text-transform:none;">{{voucher_claim_message}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <modal-detail-promo :data="voucher_selected"></modal-detail-promo>
</div>

</template>

<script>
import apiCustomer from '../../../apis/Customer'
import BlankPage from '../BlankPage.vue'
import ModalDetailPromo from '../modal/ModalDetailPromo.vue'

 
export default {
    name: "ModalGeneralVoucher.vue",
    // props : ['voucher_list' ,'voucher_total_payment'],
    props: {
        method: Function,
        voucher_list : {},
        voucher_total_payment : {},
        product_id : '',
        pagination: Object
    },
    data() {
        return {
            voucher_claim_message : '',
            voucher_selected: {},
        }
    },
    mounted(){
        this.method();
    },
    methods: {
        show_active_voucher(){
                $("#voucher_active").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
                this.timer = setTimeout(() => {
                    $("#voucher_active").fadeOut(function () {
                        $("body").removeClass('overflow-hidden');
                    });
                }, 2000);
                $("#general_voucher_card_modal").fadeOut(function () {
                    $("body").addClass('overflow-hidden');
                });
        },
        selected_voucher(item){
            apiCustomer.checkHotdelVoucher({voucher_code : item.voucher_code , voucher_id : item.id , total_payment: this.voucher_total_payment.total_payment, product_id: this.product_id}).then( response => {
                if(response.data.code == 200){
                    this.$emit('UpdateVocuherHotdeal',{calc : response.data.data.calculation_price , voucher : response.data.data.discount_amount , voucher_hotdeal : {voucher_code : item.voucher_code , total_payment: this.voucher_total_payment, is_code: item.is_code}})
                    this.voucher_claim_message = 'Voucher aktif, silahkan lanjutkan pembayaran!';
                    $('.use-voucher').addClass('active');
                }else{
                    this.voucher_claim_message = response.data.message;
                }
                this.show_active_voucher()
            });  
        },
        show_modal(item){
            this.voucher_selected = {}
            this.voucher_selected = item
            $("#promo_detail_modal").fadeIn(function () {
                $("#promo_detail_modal").addClass('overflow-scroll');
                $("body").addClass('overflow-hidden');
            });
        },
        updateParent(value) {
            this.$emit("parentMethod", value);
        }

    },
    components:{
        ModalDetailPromo,
        BlankPage,
    }
}
</script>

<style>

</style>