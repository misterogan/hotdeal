<template>
    <div id="pinpoint_address_edit_checkout_modal" class="modal">
        <div class="modal-dialog w-800">
            <div class="modal-body">
                <!-- <span class="close-modal" @click="close_modal"></span> -->
                <div class="content-modal">
                    <div class="modal-pinpoint mbottom-20">
                        <h5 class="mbottom-30">Alamat pengiriman</h5>
                        <form autocomplete="off" class="form-pin-point scroll-overflow h-450">
                            <div class="row-100 mbottom-20">
                                <h5 class="col-3">nama penerima</h5>
                                <div class="col-9 relative">
                                    <input type="text" v-model.trim="$v.form.recipientname.$model" name="recepient">
                                    <div class="error" v-if="submitStatus && !$v.form.recipientname.required">Field tidak boleh kosong</div>
                                </div>
                            </div>
                            <div class="row-100 mbottom-20">
                                <h5 class="col-3">Nomor Telepon</h5>
                                <div class="col-9 relative">
                                    <input type="number" v-model.trim="$v.form.phone.$model" name="phone">
                                    <div class="error" v-if="submitStatus && !$v.form.phone.required">Field tidak boleh kosong</div>
                                    <div class="error" v-if="submitStatus && !$v.form.phone.minLength">>Nomor telepon minimal  {{$v.form.phone.$params.minLength.min}} angka.</div>
                                </div>
                            </div>
                            <div class="row-100 mbottom-20">
                                <h5 class="col-3">Provinsi</h5>
                                <div class="col-9 relative">
                                    <div class="w-100">
                                        <Select2 class="w-100" v-model="$v.form.province.$model" :options="provinces" @select="mySelectEvent($event , 'province')" @change="changeEventSelect2($event , 'province')"/>
                                    </div>
                                    <div class="error" v-if="submitStatus && !$v.form.province.required">Field tidak boleh kosong</div>
                                </div>
                            </div>
                            <div class="row-100 mbottom-20">
                                <h5 class="col-3">Kota</h5>
                                <div class="col-9 relative">
                                    <div class="w-100">
                                        <Select2 v-model="$v.form.city.$model" :options="city" @select="mySelectEvent($event , 'city')" @change="changeEventSelect2($event , 'city')"/>
                                    </div>
                                    <div class="error" v-if="submitStatus && !$v.form.city.required">Field tidak boleh kosong</div>
                                </div>
                            </div>
                            <div class="row-100 mbottom-20">
                                <h5 class="col-3">Kecamatan</h5>
                                <div class="col-9 relative">
                                    <div class="w-100">
                                        <Select2 v-model="$v.form.suburbs.$model" :options="suburbs" @select="mySelectEvent($event , 'suburbs')" @change="changeEventSelect2($event , 'suburbs')"/>
                                    </div>
                                    <div class="error" v-if="submitStatus && !$v.form.suburbs.required">Field tidak boleh kosong</div>
                                </div>
                            </div>
                            <div class="row-100 mbottom-20">
                                <h5 class="col-3">Kelurahan</h5>
                                <div class="col-9 relative">
                                    <div class="w-100">
                                        <Select2 v-model="$v.form.area.$model" :options="area" @select="mySelectEvent($event , 'area')" @change="changeEventSelect2($event , 'area')"/>
                                    </div>
                                    <div class="error" v-if="submitStatus && !$v.form.area.required">Field tidak boleh kosong</div>
                                </div>
                            </div>
                            <div class="row-100 mbottom-20">
                                <h5 class="col-3">Kode Pos</h5>
                                <div class="col-9 relative">
                                    <input type="number" v-model="$v.form.zip_code.$model">
                                    <div class="error" v-if="submitStatus && !$v.form.zip_code.required">Field tidak boleh kosong</div>
                                </div>
                            </div>
                            <div class="row-100 mbottom-20">
                                <h5 class="col-3">Alamat</h5>
                                <div class="col-9 relative">
                                    <input type="text" v-model.trim="$v.form.address.$model" name="address">
                                    <div class="error" v-if="submitStatus && !$v.form.address.required">Field tidak boleh kosong</div>
                                </div>
                            </div>
                            <div class="row-100 mbottom-20">
                                <h5 class="col-3">Label Alamat</h5>
                                <div class="col-9 relative">
                                    <input type="text" v-model.trim="$v.form.label_name.$model">
                                    <div class="error" v-if="submitStatus && !$v.form.label_name.required">Field tidak boleh kosong</div>
                                </div>
                            </div>
                            <div class="row-100 mbottom-25">
                                <p class="fp-pink italic fs-11 fw-500 text-left w-95">Jika ingin melakukan pengiriman dengan kurir <b>same day</b> maka <b>wajib</b> mengatur lokasi pinpoint</p>
                            </div>
                            <div class="filled mbottom-20" v-if="!showMaps">
                                <div class="filled-text-maps">
                                    <img class="mright-5" src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/static/Point-Map-Icon-small_w-shadow.png" alt="" width="30">
                                    {{text_address}}
                                </div>
                                <button class="btn-select-location" v-if="!showMaps" href="javascript:void(0)" @click="toogleMaps">Pilih Lokasi</button>
                            </div>
                            <div class="row-100 mbottom-20 maps-show d-none">
                                <h5 class="col-3">Tentukan lokasi pinpoint</h5>
                                <div class="col-9 relative">
                                <input
                                        type="text"
                                        placeholder="Enter your address"
                                        v-model="address"
                                        ref="autocomplete_edit_checkout"
                                    />
                                    <!-- <div class="error" v-if="submitStatus && !$v.form.label_name.required">Field tidak boleh kosong</div> -->
                                    <div class="error" v-show="error">
                                        Silahkan isi semua field untuk memnetukan lokasi.
                                    </div>
                                </div>
                            </div>
                            <div class="pin-point maps-show d-none">
                                <h4>Tentukan titik pinpoint lokasi kamu</h4>
                                <div class="maps" id="mapedit_checkout" ref="map_edit_checkout"></div>
                                <div class="location-name">
                                    {{text_address}}
                                </div>
                            </div>
                            
                        </form>
                        <div class="row-100 align-center justify-center mtop-20 button">
                                <button class="btn-secondary mright-5" @click="cancel_modal">
                                    Batal
                                </button>
                                <button class="btn-primary mleft-5">
                                    <a @click="saveAddress">Simpan</a>
                                </button>
                            </div>
                            <div class="tnc mtop-30">
                                Dengan klik `Simpan`, Anda telah menyetujui &nbsp;
                                <router-link to="/policies?index=Aturan_dan_Kebijakan"> <b>Syarat &amp; Ketentuan</b></router-link>
                            </div>
                    </div>
                    <modal-delete-confirmation :item='deleted_item'  :deleteFor="'address'" @dodelete="dodelete($event)"></modal-delete-confirmation>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import Vuelidate from 'vuelidate'
    import Select2 from 'v-select2-component';
    Vue.use(Vuelidate)
    import Customer from '../../../../apis/Customer'
    import apiMaster from '../../../../apis/Master'
    import ProfileMenu from './ProfileMenu.vue'
    import ProfilePointInfo from './Profile.vue'
    import { required, minLength, between } from 'vuelidate/lib/validators'
    import Message from '../../../../utils/Message'
    import ModalDeleteConfirmation from '../../modal/ModalDeleteConfirmation.vue';
    
    export default {
        name: "AddressPinPointCheckoutEdit.vue",
        data(){
            return {
                spinner : false,
                showMaps : false,
                error : false,
                address: "",
                text_address : "",
                text_province : "",
                text_city : "",
                text_area : "",
                text_suburb : "",
                provinces: [],
                city: [],
                suburbs: [],
                area: [],
                showForm : false,
                EditForm : false,
                IndexOfAddress : null,
                statusButton : '',
                addresses : {},
                total_address: null,
                selected_province : 0,
                selected_city : 0,
                selected_regency : 0,
                name : '',
                submitStatus : false,
                disabledButton : false,
                deleted_item : {},
                form : {
                    itemid : '',
                    recipientname : '',
                    phone : '',
                    address : '',
                    label_name : '',
                    city : '',
                    province : '',
                    zip_code : '',
                    area: '',
                    suburbs : '',
                    latitude : '',
                    longitude : '',
                    isPrimary : false
                }
            }
        },
        props : ['item','data_index'],
        validations: {
            form : {
                recipientname: {
                    required,
                    minLength: minLength(1),
                },
                phone: {
                    required,
                    minLength: minLength(10)
                },
                address: {
                    required,
                    minLength: minLength(10)
                },
                city: {
                    required,
                },
                province: {
                    required,
                },
                suburbs: {
                    required,
                },
                area: {
                    required,
                },
                zip_code: {
                    required,
                },
                label_name: {
                    required
                }
            },
            
        },
        watch: {
            item() {
                if(Object.keys(this.item).length > 0){
                    //this.idx = this.data_index;
                    this.form.itemid = this.item.itemid
                    this.form.recipientname = this.item.recipient_name;
                    this.form.phone = this.item.phone_number;
                    this.form.address = this.item.address;
                    this.form.label_name = this.item.label_name;
                    this.form.province = this.item.province_id.id;
                    this.form.city = this.item.city_id.id;
                    this.form.suburbs = this.item.regency_id.id;
                    this.form.area = this.item.area_id.id;
                    this.form.longitude = this.item.longitude;
                    this.form.latitude = this.item.latitude;
                    this.form.isPrimary = this.item.is_primary_address
                    this.province_data()
                    this.city_data()
                    this.suburbs_data()
                    this.area_data()
                    this.text_province  = this.item.province_id.text ?? ''
                    this.text_city = this.item.city_id.text ?? ''
                    this.text_area  = this.item.area_id.name ?? ''
                    this.text_suburb = this.item.regency_id.text?? ''
                    this.text_address = this.text_province +' '+this.text_city+' '+this.text_suburb+' '+this.text_area
                    this.address = this.text_address
                    this.form.zip_code = this.item.zip_code
                }
            }
        },
        mounted(){
            var vm = this
            this.province_data(); 
            Customer.primaryAddress().then( response => {
                this.profile = response.data.data;
                this.is_loading = false
            });
            var autocomplete = new google.maps.places.Autocomplete(
                this.$refs["autocomplete_edit_checkout"],
                {
                    bounds: new google.maps.LatLngBounds(
                        new google.maps.LatLng(-6.181135320102582, 106.82687954014743)
                    ),
                    componentRestrictions: {country: "id"},
                }
            );
            autocomplete.addListener("place_changed", () => {
                if(vm.form.province == "" || vm.form.city == "" || vm.form.suburbs == "" || vm.form.area == ""){
                    return this.error = true
                }
                var place = autocomplete.getPlace();
                this.showLocationOnTheMap(
                    place.geometry.location.lat(),
                    place.geometry.location.lng()
                );
                vm.form.longitude = place.geometry.location.lng()
                vm.form.latitude = place.geometry.location.lat()
                vm.text_address = place.formatted_address
            });
        },
        methods: {
            province_data(){
                apiMaster.province().then(response => {
                    this.provinces = response.data.data;
                })
            },
            city_data(){
                apiMaster.city({province : this.form.province}).then(response => {
                    this.city = response.data.data;
                }) 
            },
            suburbs_data(){
                apiMaster.subrubs({city : this.form.city}).then(response => {
                    this.suburbs = response.data.data;
                }) 
            },
            area_data(){
                apiMaster.area({suburb_id : this.form.suburbs}).then(response => {
                    this.area = response.data.data;
                }) 
            },
            changeEventSelect2(val , type , index){
                if(type == 'province'){
                    this.city_data();
                }
                if(type == 'city'){
                    this.city_value = val
                    this.suburbs_data();
                }
                if(type == 'suburbs'){
                    this.suburbs_value = val
                    this.area_data();   
                }
                if(type == 'area'){
                    this.area_value = val
                }
            },
            mySelectEvent({id, text} , type){
                if(type == 'province'){
                   this.text_province = text
                    this.suburbs = []
                    this.area = []
                    this.form.city = ""
                    this.form.suburbs = ""
                    this.form.area = ""
                }
                if(type == 'city'){
                    this.text_city = text
                    this.area = []
                    this.form.suburbs = ""
                    this.form.area = ""
                }
                if(type == 'suburbs'){
                    this.text_suburb = text
                    this.form.area = ""
                }
                if(type == 'area'){
                    this.text_area = text
                    this.error = false
                }
                this.text_address = this.text_province+' '+this.text_city+' '+this.text_suburb+' '+this.text_area
                this.address = this.text_address
            },
            resetForm(){
                for(var key in this.form) {
                    this.form[key] = '';
                }
            },
            saveAddress(e){
                this.submitStatus = true
                if(this.disabledButton == false){
                    this.disabledButton = true;
                    if (this.$v.$invalid) {
                        this.disabledButton = false;
                    }else {
                        $("#modal_load").fadeIn();
                        Customer.update_address(this.form).then(response => {
                            this.disabledButton = false;
                            $("#modal_load").fadeOut()
                            if(response.data.code == 200){
                                this.$emit('action' , {type:'update' , data : response.data.data , idx : this.data_index})
                                window.location.reload()
                               $("#pinpoint_address_edit_checkout_modal").fadeOut(function () {
                                    // $("#pinpoint_address_edit_modal").addClass('overflow-scroll');
                                    $("body").removeClass('overflow-hidden');
                                });
                            }else{
                                Message.alert(response.message , "Informasi", 1000)
                            }
                        })
                    }
                }
            },
            locatorButtonPressed() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.getAddressFrom(
                            position.coords.latitude,
                            position.coords.longitude
                        );

                        this.showLocationOnTheMap(
                            position.coords.latitude,
                            position.coords.longitude
                        );
                    },
                    (error) => {
                        this.error =
                        "Locator is unable to find your address. Please type your address manually.";
                        this.spinner = false;
                    }
                    );
                } else {
                    this.error = error.message;
                    this.spinner = false;
                    console.log("Your browser does not support geolocation API ");
                }
            },
            getAddressFrom(lat, long) {
                axios
                    .get(
                    "https://maps.googleapis.com/maps/api/geocode/json?latlng=" +
                        lat +
                        "," +
                        long +
                        "&key=AIzaSyC-zPIfwqqllqd4f4yngXK8dLV_WCaddK0"
                    )
                    .then((response) => {
                        console.log(response)
                    if (response.data.error_message) {

                        this.error = response.data.error_message;
                        console.log(response.data.error_message);
                    } else {
                        this.address = response.data.results[0].formatted_address;
                        
                    }
                    this.spinner = false;
                    })
                    .catch((error) => {
                        this.error = error.message;
                        this.spinner = false;
                        console.log(error.message);
                    });
            },
            showLocationOnTheMap(latitude, longitude) {
                var map = new google.maps.Map(this.$refs["map_edit_checkout"], {
                    zoom: 17,
                    center: new google.maps.LatLng(latitude, longitude),
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    zoomControl: false,
                    mapTypeControl: false,
                    scaleControl: false,
                    streetViewControl: false,
                    rotateControl: false,
                    fullscreenControl: false
                });
                const icon = "https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/static/Point-Map-Icon-small_w-shadow.png";
                var myMarker = new google.maps.Marker({
                    position: new google.maps.LatLng(latitude, longitude),
                    map: map,
                    icon : icon,
                });
                let vm = this

                google.maps.event.addListener(map, 'center_changed', function() {
                    window.setTimeout(function() {
                        var center = map.getCenter();
                        myMarker.setPosition(center);
                        vm.form.longitude = center.lng()
                        vm.form.latitude = center.lat()
                        }, 10
                    );
                });
            },
            setPrimaryAddress(){
                this.form.isPrimary = !this.form.isPrimary
            },
            toogleMaps(){
                 
                this.showMaps = !this.showMaps
                
                $('.maps-show').show()
               
                if(this.form.latitude != "" && this.form.longitude != ""){
                    this.showLocationOnTheMap(this.form.latitude , this.form.longitude)
                }else{

                }
                
            },
            cancel_modal(){
                $("#pinpoint_address_edit_checkout_modal").fadeOut(function () {
                    $("body").removeClass('overflow-hidden');
                });
                $('.maps-show').hide()
                this.showMaps = false
                this.resetForm
            }
        },
        components : {
            ProfileMenu,
            ProfilePointInfo,
            Select2,
            Message,
            ModalDeleteConfirmation
        }
    }
</script>


<style>
</style>
