<template>
    <div id="voucher_card_modal" class="modal">
        <div class="modal-dialog w-800">
            <div class="modal-body">
                <span class="close-modal"></span>
                <div class="content-modal">
                    <h4>Voucher</h4>
                    <div class="search">
                        <div class="row-100">
                            <div class="col-12 relative">
                                <input name="search" class="input-search" type="search" id="search" autocomplete="off" value="" placeholder="Search Voucher" />
                                <img class="ic_search" src="/img/ic_search.svg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="voucher-card-promo">
                        <div class="row-voucher">
                            <div class="col-6" v-if="Object.keys(voucher_list).length > 0" v-for="(item, index) in voucher_list" :key="index">
                                <div class="card-promo">
                                    <div class="container-img">
                                        <img :src="item.voucher_image" :alt="item.voucher_name">
                                    </div>
                                    <div class="desc-promo">
                                        <h5>{{item.voucher_name}}</h5>
                                        <h6>
                                            {{item.voucher_decription}}
                                        </h6>
                                        <div class="col-btn">
                                            <h6 class="btn-link" href="javascript:void(0)" @click="show_detail">
                                                Selengkapnya
                                            </h6>
                                            <button class="btn-primary" href="javascript:void(0)" @click="selected_voucher(item)">
                                                Gunakan Voucher
                                            </button>
                                        </div>                           
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import apiCustomer from '../../../apis/Customer'
export default {
    name: "ModalVoucher.vue",
    props : ['voucher_list' ,'voucher_total_payment'],
    data() {
        return {
        }
    },
    mounted(){
    },
    methods: { 
        close_modal(){
            //close modal
        },
        show_active_voucher(){
            $("#voucher_active").fadeIn(function () {
                $("body").addClass('overflow-hidden');
            });
            $("#voucher_card_modal").fadeOut(function () {
                $("body").addClass('overflow-hidden');
            });
        },
        selected_voucher(item){
            apiCustomer.checkMerchantVoucher({vendor_id : item.vendor_id , voucher_code : item.voucher_code , voucher_id : item.voucher_id , total_payment: this.voucher_total_payment}).then( response => {
                if(response.data.code == 200){
                    this.$emit('UpdateVocuherMerchant',{vendor_id : item.vendor_id, item : response.data.data})
                    this.show_active_voucher()
                }
            });  
        },
        show_detail(){
            $("#promo_detail_modal").fadeIn(function () {
                $("#promo_detail_modal").addClass('overflow-scroll');
                $("body").addClass('overflow-hidden');
            });
        }
    },
    mounted(){
        
    },
    components:{
    }
}
</script>

<style>

</style>