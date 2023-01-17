<template>
    <div class="row-hd">
        <div class="col3-flex">
            <div class="flex-basis-40">
                <profile-menu></profile-menu>
                <profile-point-info v-if="Object.keys(profile).length > 0" :profile="profile.users"></profile-point-info>
            </div>
            <div class="flex-basis-60">
                <div class="box3-shadow-white">
                    <div class="mbottom-10" v-if="is_loading">
                        <div class="ph-row justify-between mbottom-45">
                            <div class="d-block ph-col-2 bg-placeholder ph-h15"></div>
                        </div>
                        <div class="ph-row mbottom-35">
                            <div class="ph-col-2 bg-placeholder ph-h15 mright-20"></div>
                            <div class="ph-col-4 bg-placeholder ph-h15"></div>
                        </div>
                        <div class="ph-row mbottom-20">
                            <div class="ph-col-2 bg-placeholder ph-h15 mright-20"></div>
                            <div class="ph-col-6 ph-h75 bg-placeholder ph-h15"></div>
                        </div>
                        <div class="ph-row">
                            <div class="ph-col-2 bg-placeholder ph-h15 mright-20"></div>
                            <div class="ph-col-6 ph-h75 bg-placeholder ph-h15"></div>
                        </div>
                    </div>
                    <div v-if="!is_loading">
                        <h2 class="mbottom-30">daftar alamat</h2>  
                        <div class="d-flex align-center justify-between">
                            <h5 class="fw-700 mright-15">Alamat pengiriman</h5>
                            <button class="btn-link" v-if="total_address < 5" @click="show_modal_add">+ Tambah Alamat</button>
                        </div>
                        <div class="mbottom-20" v-if="showForm">
                        <form autocomplete="off">
                            <h5 class="mbottom-10">Nama Penerima</h5>
                            <input type="text" v-model.trim="$v.form.recipientname.$model" name="recepient">
                            <div class="error" v-if="submitStatus && !$v.form.recipientname.required">Field tidak boleh kosong</div>
                            <!-- <div class="error" v-if="submitStatus && !$v.form.recipientname.minLength">Name must have at least {{$v.form.recipientname.$params.minLength.min}} letters.</div> -->
                            
                            <div class="mbottom-20"></div>

                            <h5 class="mbottom-10">Nomor Telepon</h5>
                            <input type="number" v-model.trim="$v.form.phone.$model" name="phone">
                            <div class="error" v-if="submitStatus && !$v.form.phone.required">Field tidak boleh kosong</div>
                            <div class="error" v-if="submitStatus && !$v.form.phone.minLength">>Nomor telepon minimal  {{$v.form.phone.$params.minLength.min}} angka.</div>
                        
                            <div class="mbottom-20"></div> 

                            <h5 class="mbottom-10">Alamat</h5>
                            <input type="text" v-model.trim="$v.form.address.$model" name="address">
                            <div class="error" v-if="submitStatus && !$v.form.address.required">Field tidak boleh kosong</div>

                            <div class="mbottom-20"></div> 

                            <h5 class="mbottom-10">Provinsi</h5>
                            <div>
                                <Select2 v-model="$v.form.province.$model" :options="provinces" @change="changeEventSelect2($event , 'province')"/>
                            </div>
                            <div class="error" v-if="submitStatus && !$v.form.province.required">Field tidak boleh kosong</div>
                            
                            <div class="mbottom-20"></div> 

                            <h5 class="mbottom-10">Kota</h5>
                            <Select2 v-model="$v.form.city.$model" :options="city" @change="changeEventSelect2($event , 'city')"/>
                            <div class="error" v-if="submitStatus && !$v.form.city.required">Field tidak boleh kosong</div>

                            <div class="mbottom-20"></div> 

                            <h5 class="mbottom-10">Kecamatan</h5>
                            <Select2  v-model="$v.form.suburbs.$model" :options="suburbs" @change="changeEventSelect2($event , 'suburbs')"/>
                            <div class="error" v-if="submitStatus && !$v.form.suburbs.required">Field tidak boleh kosong</div>
                            
                            <div class="mbottom-20"></div> 

                            <h5 class="mbottom-10">Kelurahan</h5>
                            <Select2 v-model="$v.form.area.$model" :options="area" @change="changeEventSelect2($event , 'area')"/>
                            <div class="error" v-if="submitStatus && !$v.form.area.required">Field tidak boleh kosong</div>
                            
                            <div class="mbottom-20"></div> 

                            <h5 class="mbottom-10">Kode Pos</h5>
                            <input type="number" v-model="$v.form.zip_code.$model">
                            <div class="error" v-if="submitStatus && !$v.form.zip_code.required">Field tidak boleh kosong</div>

                            <div class="mbottom-20"></div> 

                            <h5 class="mbottom-10">Label Alamat</h5>
                            <input type="text" v-model.trim="$v.form.label_name.$model">
                            <div class="error" v-if="submitStatus && !$v.form.label_name.required">Field tidak boleh kosong</div>
                        </form>
                        <div class="btn-confirm mtop-20">
                            <button class="btn-secondary">
                                <a href="">Batal</a>
                            </button>
                            <button class="btn-primary">
                                <a @click="saveAddress" href="javascript:void(0)">Simpan</a>
                            </button>
                        </div>
                        </div>
                        <div v-if="Object.keys(addresses).length > 0">
                            <div class="row-address" v-for="(item , index) in addresses" :key="index">
                                <div class="d-flex mtop-20 column-mobile">
                                    <h5 class="number-of-address">Alamat {{index+1}}</h5>
                                    <div class="w-100">
                                        <div class="address mbottom-20" v-bind:class="item.is_primary_address == true ? 'main' : ''">
                                            <h5 class="label-address mright-5">{{item.label_name}}</h5>  
                                            <h5>{{item.recipient_name}}</h5>
                                            <h5>{{item.address}}
                                                <div v-if="item.city">{{item.city.name}},  {{item.zip_code}} </div>
                                            </h5>
                                        </div>
                                        <div class="btn-option">
                                            <div class="option-address" v-if="item.is_primary_address == false">
                                                <div class="d-flex align-center" @click="setPrimaryAddress(item.itemid , index)">
                                                    <img class="mright-5" src="/img/assets_uncheck_purple.svg" alt="" width="16">
                                                    <span class="fp-black fs-13"> Gunakan Sebagai Alamat Utama </span>
                                                </div>
                                            </div>
                                            <div class="d-flex align-center" v-else>
                                                <img class="mright-5" src="/img/assets_checklist_purple.svg" alt="" width="16">
                                                <span class="fp-black fs-13"> Gunakan Sebagai Alamat Utama </span>
                                            </div>
                                            <div class="edit-delete">
                                                <span v-on:click="show_modal_edit(index , item)">Ubah Alamat</span>
                                                <span @click="RemoveAddress(index , item)">Hapus Alamat</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <modal-delete-confirmation :item='deleted_item'  :deleteFor="'address'" @dodelete="dodelete($event)"></modal-delete-confirmation>
            <modal-pin-point @action="response_after_action($event)"></modal-pin-point>
            <modal-pin-point-edit :item="item_edit" @action="response_after_action($event)" :data_index="data_index"></modal-pin-point-edit>
        </div>
    </div>
</template>

<script>
    import apiCustomer from '../../apis/Customer'
    import ProfileMenu from '../desktop/component/profile/ProfileMenu.vue'
    import ProfilePointInfo from '../desktop/component/profile/Profile.vue'
    import HighLightTwoColumn from './component/product/HighLightTwoColumn.vue'
    import HighLightImageOnly from './component/product/HighLightImageOnly.vue'
    import RejekiNomplokBanner from './RejekiNomplokBanner.vue'
    import BlankPage from '../../components/desktop/BlankPage.vue'

    import Vue from 'vue'
    import Vuelidate from 'vuelidate'
    import Select2 from 'v-select2-component';
    Vue.use(Vuelidate)
    import Customer from '../../apis/Customer'
    import apiMaster from '../../apis/Master'
    import { required, minLength, between } from 'vuelidate/lib/validators'
    import Message from '../../utils/Message'
    import ModalDeleteConfirmation from './modal/ModalDeleteConfirmation.vue'
    import ModalPinPoint from './modal/ModalPinPoint.vue'
    import ModalPinPointEdit from '../desktop/component/profile/AddressPinPointEdit.vue'

    export default {
        name: "PersonalInfoAddress.vue",
        data(){
            return{
                is_loading : true,
                data_index : '',
                item_edit : {},
                profile : {},
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
                    recipientname : '',
                    phone : '',
                    address : '',
                    label_name : '',
                    city : '',
                    province : '',
                    zip_code : '',
                    area: '',
                    suburbs : ''
                }
            }
        },
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
        components : {
            ProfileMenu,
            ProfilePointInfo,
            HighLightTwoColumn,
            HighLightImageOnly,
            RejekiNomplokBanner,
            BlankPage,
            Select2,
            Message,
            ModalDeleteConfirmation,
            ModalPinPoint,
            ModalPinPointEdit
        },
        mounted(){
            this.get_address_by_id()
            apiCustomer.primaryAddress().then( response => {
                this.profile = response.data.data;
                this.is_loading = false
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
            changeEventSelect2(val , type){
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
            mySelectEvent({id, text}){
            },
            get_address_by_id(){
                Customer.get_address_list().then(response => {
                    this.addresses = response.data.data.address;
                    this.total_address = this.addresses.length;
                })
            },
            showFormFunction(){
                this.province_data()
                this.resetForm();
                this.showForm = !this.showForm
                this.statusButton = 'new'
            },
            RemoveAddress(index , item){
                this.deleted_item = {index : index , item : item}
                $("#delete_modal_confirmation").fadeIn();
            },
            dodelete(event){
                if(event.for == 'address'){
                    $("#delete_modal_confirmation").fadeOut();
                    Customer.remove_address({id : event.item.item.itemid}).then(response => {
                        if(response.data.code == 200){
                            this.addresses.splice(event.item.index , 1);
                            this.total_address = this.addresses.length - 1;
                        }
                        Message.alert(response.data.message , 'Informasi' , 1500)
                    })
                    this.deleted_item = {index : index , item : item}
                    
                }
            },
            EditFormFunction(i , item){
                this.statusButton = 'update'
                this.form.recipientname = item.recipient_name;
                this.form.phone = item.phone_number;
                this.form.address = item.address;
                this.form.label_name = item.label_name;
                this.form.province = item.province_id.id;
                this.form.city = item.city_id.id;
                this.form.suburbs = item.regency_id.id;
                this.form.area = item.area_id.id;
                this.province_data()
                this.city_data()
                this.suburbs_data()
                this.area_data()
                this.form.zip_code = item.zip_code;
                this.EditForm = !this.EditForm;
                this.IndexOfAddress = i;
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
                        if(this.statusButton == 'new'){
                            Customer.save_address(this.form).then(response => {
                                this.disabledButton = false;
                                $("#modal_load").fadeOut()
                                if(response.data.code == 200){
                                    window.location.reload()
                                }
                            })
                        }else{
                            this.form.itemid = this.addresses[this.IndexOfAddress].itemid;
                            Customer.update_address(this.form).then(response => {
                                if(response.data.code == 200){
                                    this.addresses[this.IndexOfAddress] = response.data.data;
                                    this.EditForm = false;
                                }else{
                                    alert(response.data.message);
                                }
                                this.disabledButton = false;
                                $("#modal_load").fadeOut()
                            })
                        }
                        
                    }
                }
            },
            setPrimaryAddress(itemid , index){
                Customer.setAsPrimary({itemid : itemid}).then(response => {
                    if(response.data.code == 200){
                        for(const item of this.addresses){
                            item.is_primary_address = false;
                        }
                        this.addresses[index].is_primary_address = true
                        Message.alert("Berhasil mengubah alamat utama" , "Informasi" , 1000);
                    }else{
                         Message.alert(response.data.message , "Informasi");
                    }
                })
            },
            show_modal_add(){
                $("#pinpoint_address_modal").fadeIn(function () {
                    $("#pinpoint_address_modal").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            show_modal_edit(i , item){
                //alert(i)
                this.data_index = i;
                this.item_edit = item;
                $("#pinpoint_address_edit_modal").fadeIn(function () {
                    // $("#pinpoint_address_edit_modal").addClass('overflow-scroll');
                    $("body").addClass('overflow-hidden');
                });
            },
            response_after_action(event){
                if(event.type == 'update'){
                     this.addresses[event.idx].address = event.data.address;
                     this.addresses[event.idx].label_name = event.data.label_name;
                    this.addresses[event.idx].zip_code = event.data.zip_code
                    this.addresses[event.idx] = event.data;
                }
            }
        },
    }
</script>