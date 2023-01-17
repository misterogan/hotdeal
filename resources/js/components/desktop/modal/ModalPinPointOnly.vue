<template>
    <div id="pinpoint_only_modal" class="modal">
        <div class="modal-dialog w-800">
            <div class="modal-body">
                <div class="content-modal">
                    <div class="modal-pinpoint mbottom-20">
                        <h5 class="mbottom-30">Tentukan Pin Point</h5>
                        <form autocomplete="off" class="form-pin-point scroll-overflow h-450">
                            <div class="row-100 mbottom-20">
                                <h5 class="col-3">Tentukan lokasi pinpoint</h5>
                                <div class="col-9 relative">
                                    <input type="text" placeholder="Pilih Lokasi" v-model="address"
                                        ref="autocomplete" />
                                    <!-- <div class="error" v-if="submitStatus && !$v.form.label_name.required">Field tidak boleh kosong</div> -->
                                    <div class="error" v-show="error">
                                        Silahkan isi semua field untuk menentukan lokasi.
                                    </div>
                                </div>
                            </div>
                            <div class="row-100 mbottom-25">
                                <p class="fp-pink italic fs-11 fw-500 text-left w-95">Jika ingin melakukan pengiriman dengan
                                    kurir <b>same day</b> maka <b>wajib</b> mengatur lokasi pinpoint</p>
                            </div>
                            <div class="d-block mbottom-15">
                                <h4>Tentukan titik pinpoint lokasi kamu</h4>
                                <div class="maps" id="map" ref="map"></div>
                                <div class="location-name">{{ text_address }}</div>
                            </div>
                        </form>
                        <!-- <div @click="setPrimaryAddress()" class="d-flex align-center mtop-40 pointer">
                            <img class="mright-5" :src="
                                //   form.isPrimary
                                true
                                    ? '/img/assets_checklist_purple.svg'
                                    : '/img/assets_uncheck_purple.svg'
                            " alt="" width="16" />
                            <a>Gunakan Sebagai Alamat Utama</a>
                        </div> -->
                        <div class="row-100 align-center justify-center mtop-20 button">
                            <button class="btn-secondary mright-5" @click="cancel_modal">
                                <a href="javascript:void(0)">Batal</a>
                            </button>
                            <button class="btn-primary mleft-5" @click.prevent="saveAddress">
                                <a href="javascript:void(0)">Simpan</a>
                            </button>
                        </div>
                        <div class="tnc mtop-30">
                            Dengan klik 'Simpan', Anda telah menyetujui &nbsp;
                            <router-link to="/policies?index=Aturan_dan_Kebijakan">
                                <b>Syarat &amp; Ketentuan</b>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AddressPinPoint from "../component/profile/AddressPinPoint.vue";
import Customer from "../../../apis/Customer";
import apiMaster from "../../../apis/Master";

export default {
    name: "ModalPinPointOnly.vue",
    data(){
            return {
                spinner : false,
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
                form: {
                    id : '',
                    latitude : '',
                    longitude : '',
                }

            }
        },
        mounted(){
            var vm = this
            this.province_data(); 
            Customer.primaryAddress().then( response => {
                this.profile = response.data.data;
                this.text_province = this.profile.address.province_id.name;
                this.text_city = this.profile.address.city_id.name;
                this.text_area = this.profile.address.area_id.name;
                this.text_suburb = this.profile.address.regency_id.name;
                this.address = this.text_province+' '+this.text_city+' '+this.text_suburb+' '+this.text_area
                this.is_loading = false
            });
            var autocomplete = new google.maps.places.Autocomplete(
                this.$refs["autocomplete"],
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
            saveAddress(e){
                this.submitStatus = true
                if(this.disabledButton == false){
                    this.disabledButton = true;
                    $("#modal_load").fadeIn();
                    Customer.set_pinpoint(this.form).then(response => {    
                        this.disabledButton = false;
                        $("#modal_load").fadeOut();
                        if(response.data.code == 200){
                            // this.$parent.lat = response.data.data.lat;
                            this.$emit('latitude', response.data.data.lat);
                            $('#pinpoint-null').hide();
                            $("#pinpoint_only_modal").fadeOut();
                            $("body").removeClass('overflow-hidden');
                        }
                    })
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
                var map = new google.maps.Map(this.$refs["map"], {
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
            cancel_modal(){
                $("#pinpoint_only_modal").fadeOut(function () {
                    $("#pinpoint_only_modal").addClass('overflow-scroll');
                    $("body").removeClass('overflow-hidden');
                    // $("overlay-primary").removeClass('active');
                });
            }
        },
    components: {
        AddressPinPoint,
    },
};
</script>

<style>

</style>
