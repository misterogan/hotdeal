<template>
    <div class="card-lg mbottom-20">
        <div class="co-address">
            <h3 class="nunito-title mbottom-20">Pengiriman</h3>
            <div class="mobile-shadow">
                <h5 class="fp-black mbottom-20">Alamat Pengiriman</h5>
                <div class="detail-address-co" v-if="Object.keys(address).length > 0">
                    <b id="label-address" class="fs-black text-uppercase">{{address.address.label_name}}</b>
                    <div class="d-flex align-center">
                        <h5>Penerima :</h5>&nbsp;
                        <h5 id="recepient-name">{{address.address.recipient_name}}</h5> 
                    </div>
                    <div class="text-capitalize d-flex align-center">
                        <h5 id="address" v-if="address.address.province_id != null">{{address.address.address}} &nbsp; </h5>
                        <h5 id="province" v-if="address.address.province_id != null">{{address.address.province_id.name}}, &nbsp;</h5>
                        <h5 id="regency" v-if="address.address.regency_id != null">{{address.address.regency_id.name}}, &nbsp;</h5>
                        <h5 id="zip-code">{{address.address.zip_code}} </h5>
                    </div>
                    <div class="d-flex align-center">
                        <h5>Telepon :</h5>&nbsp;
                        <h5 id="phone-number">{{address.address.phone_number}}</h5>
                    </div>    
                </div>
            </div>
            <div class="row-100 mbottom-10 option-co-btn">
                <button class="btn-primary-square mright-10" @click="show_modal_edit">Ubah Alamat</button>
                <button class="btn-secondary-square mright-10" v-show="pin" @click="show_modal_edit_pin_point">
                    Pin Point
                    <img src="/img/pinpoint.svg" alt="">
                </button>
                <button class="btn-secondary-square" @click="show_modal">Pilih dari Daftar Alamat</button>
            </div>               
        </div>
        <modal-address @change="address_change()"></modal-address>
        <modal-pin-point-edit :item="item_edit" :data_index="data_index"></modal-pin-point-edit>
        <modal-change-address-detail :item="item_edit"></modal-change-address-detail>
        <!-- <address-pin-point-edit :item="item_edit" :data_index="data_index" @action="response_after_action($event)"></address-pin-point-edit> -->
    </div>
</template>
<script>
    import apiCustomer from '../../../../apis/Customer'
    import ModalAddress from '../../modal/ModalAddress.vue'
    import ModalPinPointEdit from '../profile/AddressPinPointEdit.vue'
    import ModalChangeAddressDetail from '../../modal/ModalChangeAddressDetail.vue'
    import AddressPinPointEdit from '../profile/AddressPinPointEditCheckout.vue'


    export default {
        components: { 
            ModalAddress, 
            ModalPinPointEdit,
            ModalChangeAddressDetail,
            AddressPinPointEdit
        },
        name: "CheckoutProfile.vue",
        data(){
            return{
                address :{},
                item_edit: {},
                data_index: ''
            }
        },
        props : ['pin'],
        mounted(){
            this.primaryAdress();
        },
        methods: {
            primaryAdress(){
                apiCustomer.primaryAddress().then( response => {
                    this.address = response.data.data;
                    this.$emit('pick_location_status' , response.data.data.address.is_picked_location)
                    if(Object.keys(this.address.address).length < 1){
                        this.show_modal()
                    }
                });
            },
            show_modal(){
                $("#change_address_modal").fadeIn(function () {
                    $("#change_address_modal").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_edit(){
                apiCustomer.primaryAddress().then( response => {
                    this.address = response.data.data;
                    this.item_edit = this.address.address
                    $("#change_detail_address").fadeIn(function () {
                        $("body").addClass('overflow-hidden');
                    });
                });
            },
            show_modal_edit_pin_point(){
                 apiCustomer.primaryAddress().then( response => {
                    // this.address = response.data.data.address;
                    $("#pinpoint_address_edit_checkout_modal").fadeIn(function () {
                        $("#pinpoint_address_edit_checkout_modal").addClass('overflow-scroll');
                        $("body").addClass('overflow-hidden');
                    });
                });
            },
            address_change(){
                location.reload()
            },
        }
    }
</script>