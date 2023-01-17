<template>
   <div class="co-courier">
        <div class="courier">
            <h4 class="fs-black mbottom-20 fs-16">Pengiriman</h4>
            <div class="select-courier" :data-id="index" v-if="Object.keys(logistics).length > 0">
                <div class="default courier" v-if="!is_select">Pilih Pengiriman</div>
                <div class="default courier selected" v-else>
                    <img class="courier-logo" :src="selected_courier.logistic.logo_url">
                    <div class="courier-est" >
                        <h6>Estimasi Sampai</h6>&nbsp; <h6>{{selected_courier.min_day + ' - ' + selected_courier.max_day}} hari</h6>
                    </div>
                </div>
                <div :class="'courier-expand toggle-expand-' + index">
                    <div class="close-div d-none">
                        <img src="/img/grey_bar.svg" alt="">
                    </div>
                    <div v-if="Object.keys(logistics.logistic.direct).length > 0 ">
                        <h5 class="fs-black mbottom-20 title-mobile">Same Day / Instant</h5>
                        <div class="courier-list" v-for="(item , ilogistic) in logistics.logistic.direct" :key="ilogistic">
                            <img class="courier-logo" :src="item.logistic.logo_url" :alt="item.logistic.name" @click="func_logistic(item.logistic.id , item.final_price , item.rate.id, item , true)">
                            <div class="courier-est" @click="func_logistic(item.logistic.id , item.final_price , item.rate.id, item , true)">
                                <div class="d-flex align-center">
                                    <span class="fp-black mright-10">{{item.logistic.name}}</span>
                                    <span class="fp-pink mright-10">{{item.final_price | RupiahFormat}} </span>
                                    <!-- <span class="fp-black mright-10">{{item.discount_value | RupiahFormat}}</span> -->
                                </div>
                                <div class="d-flex align-center">
                                    <h6 class="mright-5">Estimasi Sampai</h6>
                                    <h6>{{item.min_day + ' - ' + item.max_day}} hari</h6>
                                </div>
                                <div class="d-flex align-center pointer show-pin">
                                    <span class="fp-red fs-12 fw-400 mright-25" id="pinpoint-null" v-if="lat == null">Pin point alamat belum dipilih</span>
                                    <div class="pinpoint" @click="pinPoint()">
                                        <img width="12" src="img/pinpoint.svg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="Object.keys(logistics.logistic.nondirect).length > 0 ">
                        <h5 class="fs-black mbottom-20">Reguler</h5>
                        <div class="courier-list" v-for="(item , ilogistic) in logistics.logistic.nondirect" :key="ilogistic">
                            <div class="courier-label d-flex align-center">
                                <img class="courier-logo" :src="item.logistic.logo_url" :alt="item.logistic.name">
                                <div class="courier-est">
                                   <div class="d-flex align-center">
                                        <span class="fp-black mright-10">{{item.logistic.name}}</span>
                                        <span class="fp-pink mright-10">{{item.final_price | RupiahFormat}}</span>
                                        <s class="fs-grey mright-10" v-if="item.discount_value > 0">{{(item.final_price + item.discount_value) | RupiahFormat}}</s>
                                    </div>
                                    <div class="d-flex align-center">
                                        <h6>Estimasi Sampai</h6>&nbsp;
                                        <h6>{{item.min_day + ' - ' + item.max_day}} hari</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="courier-radio">
                                <input type="radio" :value="ilogistic" @change="func_logistic(item.logistic.id , item.final_price , item.rate.id, item , false)" :name="'courier'+index" :id="item.logistic.code+item.logistic.id+item.rate.id+index" />
                                <label :for="item.logistic.code+item.logistic.id+item.rate.id+index"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <img :class="'dropdown courier-' + index" src="/img/dropdown-purple.svg"></img>
            </div>
            <div v-else> Silahkan Tambahkan Alamat pengiriman terlebih dahulu.</div>
        </div>
        <address-pin-point-edit :item="address" @action="response_after_action($event)" :data_index="0"></address-pin-point-edit>
        <modal-pin-point-only @latitude="setLat"></modal-pin-point-only>
    </div>
</template>
<script> 
    import Customer from '../../../../apis/Customer'
    import AddressPinPointEdit from '../profile/AddressPinPointEditCheckout.vue'
    import ModalPinPoint from '../../modal/ModalPinPoint.vue'
    import ModalPinPointOnly from '../../modal/ModalPinPointOnly.vue'
    import Message from '../../../../utils/Message'

    export default {
        data(){
            return {
                selected : [],
                address : {},
                selected_courier : {},
                is_select: false,
                lat : '',
                lng : ''
            }
        },
        props : ['index' ,'logistics','pin'],
        name: "CheckoutCourier.vue",
        mounted(){
            Customer.primaryAddress().then( response => {
                this.lat = response.data.data.address.latitude
                // this.lng = response.data.data.address.longitude
            })
        },
        methods: {
            func_logistic(id , price , rate, item, direct){
                if(direct == true){
                    $('input[type="radio"]').prop('checked', false);
                    if(this.lat == null){
                        return Message.alert("Pin point belum diatur!" ,'Informasi');
                    }

                }
                this.selected_courier = item
                this.is_select = true
                var vendor = this.logistics.vendor.id
                this.$emit('courier_selected' , { vendor : vendor , logistic_id : id ,rate_id : rate , price: price , detail : item , direct:direct , index : this.index });
                window.scrollTo(0,0);
            },
            show_modal_edit_pin_point(){
                 Customer.primaryAddress().then( response => {
                    this.address = response.data.data.address;
                    $("#pinpoint_address_edit_checkout_modal").fadeIn(function () {
                        $("#pinpoint_address_edit_checkout_modal").addClass('overflow-scroll');
                        $("body").addClass('overflow-hidden');
                    });
                });
            },
            pinPoint(){ 
                $("#pinpoint_only_modal").fadeIn(function () {
                    $("#pinpoint_only_modal").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            setLat(lat){
                this.lat = lat;
            }
        },
        components : {
            AddressPinPointEdit, ModalPinPoint, ModalPinPointOnly, Message
        },
    }
</script>