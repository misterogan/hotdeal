<template>
    <div>
        <div class="modal-change-address">
            <span class="close-modal"></span>
            <div class="d-flex align-center mbottom-15">
                <h3 class="nunito-title text-center w-100">pilih alamat pengiriman</h3>
            </div>
            <div class="add-address pointer" v-if="total_address < 5" v-on:click="showFormFunction">Tambah Alamat Baru</div>
            <div class="form-change-address" v-if="showForm">
                <form autocomplete="off" class="mbottom-20">
                    <div class="row-100 mbottom-20">
                        <h5 class="col-3 col-sm-12">Nama Penerima</h5>
                        <div class="col-9 col-sm-12 relative">
                            <input type="text" v-model.trim="$v.form.recipientname.$model" name="recepient">
                            <div class="error" v-if="submitStatus && !$v.form.recipientname.required">Field tidak boleh kosong</div>
                            <!-- <div class="error" v-if="submitStatus && !$v.form.recipientname.minLength">Name must have at least {{$v.form.recipientname.$params.minLength.min}} letters.</div> -->
                        </div>
                    </div>
                    <div class="row-100 mbottom-20">
                        <h5 class="col-3 col-sm-12">Nomor Telepon</h5>
                        <div class="col-9 col-sm-12 relative">
                            <input type="number" v-model.trim="$v.form.phone.$model" name="phone">
                            <div class="error" v-if="submitStatus && !$v.form.phone.required">Field tidak boleh kosong</div>
                            <div class="error" v-if="submitStatus && !$v.form.phone.minLength">>Nomor telepon minimal  {{$v.form.phone.$params.minLength.min}} angka.</div>
                        </div>
                    </div>
                    <div class="row-100 mbottom-20">
                        <h5 class="col-3 col-sm-12">Alamat</h5>
                        <div class="col-9 col-sm-12 relative">
                            <input type="text" v-model.trim="$v.form.address.$model" name="address">
                            <div class="error" v-if="submitStatus && !$v.form.address.required">Field tidak boleh kosong</div>
                        </div>
                    </div>
                    <div class="row-100 mbottom-20">
                        <h5 class="col-3 col-sm-12">Provinsi</h5>
                        <div class="col-9 col-sm-12 relative">
                            <div>
                                <Select2 v-model="$v.form.province.$model" :options="provinces" @change="changeEventSelect2($event , 'province')"/>
                            </div>
                            <div class="error" v-if="submitStatus && !$v.form.province.required">Field tidak boleh kosong</div>
                        </div>
                    </div>
                    <div class="row-100 mbottom-20">
                        <h5 class="col-3 col-sm-12">Kota</h5>
                        <div class="col-9 col-sm-12 relative">
                            <Select2 v-model="$v.form.city.$model" :options="city" @change="changeEventSelect2($event , 'city')"/>
                            <div class="error" v-if="submitStatus && !$v.form.city.required">Field tidak boleh kosong</div>
                        </div>
                    </div>
                    <div class="row-100 mbottom-20">
                        <h5 class="col-3 col-sm-12">Kecamatan</h5>
                        <div class="col-9 col-sm-12 relative">
                            <Select2  v-model="$v.form.suburbs.$model" :options="suburbs" @change="changeEventSelect2($event , 'suburbs')"/>
                            <div class="error" v-if="submitStatus && !$v.form.suburbs.required">Field tidak boleh kosong</div>
                        </div>
                    </div>
                    <div class="row-100 mbottom-20">
                        <h5 class="col-3 col-sm-12">Kelurahan</h5>
                        <div class="col-9 col-sm-12 relative">
                            <Select2 v-model="$v.form.area.$model" :options="area" @change="changeEventSelect2($event , 'area')"/>
                            <div class="error" v-if="submitStatus && !$v.form.area.required">Field tidak boleh kosong</div>
                        </div>
                    </div>
                    <div class="row-100 mbottom-20">
                        <h5 class="col-3 col-sm-12">Kode Pos</h5>
                        <div class="col-9 col-sm-12 relative">
                            <input type="number" v-model="$v.form.zip_code.$model">
                            <div class="error" v-if="submitStatus && !$v.form.zip_code.required">Field tidak boleh kosong</div>
                        </div>
                    </div>
                    <div class="row-100 mbottom-20">
                        <h5 class="col-3 col-sm-12">Label Alamat</h5>
                        <div class="col-9 col-sm-12 relative">
                            <input type="text" v-model.trim="$v.form.label_name.$model">
                            <div class="error" v-if="submitStatus && !$v.form.label_name.required">Field tidak boleh kosong</div>
                        </div>
                    </div>
                </form>
                <div class="row-100 align-center justify-center mtop-20 mbottom-20">
                    <button class="btn-secondary mright-5">
                        <a href="">Batal</a>
                    </button>
                    <button class="btn-primary mleft-5">
                        <a @click="saveAddress" href="javascript:void(0)">Simpan</a>
                    </button>
                </div>
            </div>
            <div class="mbottom-20" v-if="Object.keys(addresses).length > 0">
                <div class="mbottom-10" v-for="(item , index) in addresses" :key="index">
                    <div class="row-hd align-center">
                        <div class="col-10 address" v-bind:class="item.is_primary_address == true ? 'main' : ''">
                            <h5 class="text-uppercase mbottom-10">{{item.label_name}}</h5>   
                            <h5 class="fw-400">Penerima : {{item.recipient_name}}</h5>
                            <h5 class="fw-400">{{item.address}}
                                <span v-if="item.city">{{item.city.name}}</span>,  {{item.zip_code}}
                            </h5>
                        </div>
                        <div class="col-2 d-flex justify-right">
                            <div class="option-address" v-if="item.is_primary_address == false" style="cursor: pointer;">
                                <div class="d-flex align-center" @click="setPrimaryAddress(item.itemid , index)">
                                    <img class="mright-5" src="/img/assets_uncheck_purple.svg" alt="" width="16">
                                </div>
                            </div>
                            <div class="d-flex align-center" v-else>
                                <img class="mright-5" src="/img/assets_checklist_purple.svg" alt="" width="16">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-center">
                        <div class="edit-delete mleft-5">
                            <span class="fp-purple fs-11 fw-600 pointer" v-on:click="EditFormFunction(index , item)">Ubah Alamat</span>
                            <span class="fp-purple fs-11 fw-600 mleft-5 pointer" @click="RemoveAddress(index , item)">Hapus Alamat</span>
                        </div>
                    </div>
                    <div class="form-change-address edit" v-if="EditForm && IndexOfAddress == index">
                        <div class="row-100 mbottom-20">
                            <h5 class="col-3 col-sm-12">Nama Penerima</h5>
                            <div class="col-9 col-sm-12 relative">
                                <input type="text" v-model.trim="$v.form.recipientname.$model">
                                <div class="error" v-if="submitStatus && !$v.form.recipientname.required">Field tidak boleh kosong</div>
                                <!-- <div class="error" v-if="submitStatus && !$v.form.recipientname.minLength">Nama  {{$v.form.recipientname.$params.minLength.min}} letters.</div> -->
                            </div>
                        </div>
                        <div class="row-100 mbottom-20">
                            <div class="col-3 col-sm-12"><h5>Nomor Telepon</h5></div>
                            <div class="col-9 col-sm-12 relative">
                                <input type="text" v-model.trim="$v.form.phone.$model">
                                <div class="error" v-if="submitStatus && !$v.form.phone.required">Field tidak boleh kosong</div>
                                <div class="error" v-if="submitStatus && !$v.form.phone.minLength">Nomor telepon minimal {{$v.form.phone.$params.minLength.min}} angka.</div>
                            </div>
                        </div>
                        <div class="row-100 mbottom-20">
                            <h5 class="col-3 col-sm-12">Alamat</h5>
                            <div class="col-9 col-sm-12">
                                <input type="text" class="address" v-model.trim="$v.form.address.$model">
                                <div class="error" v-if="submitStatus && !$v.form.address.required">Field tidak boleh kosong</div>
                            </div>
                        </div>
                        <div class="row-100 mbottom-20">
                            <h5 class="col-3 col-sm-12">Provinsi</h5>
                            <div class="col-9 col-sm-12">
                                <div>
                                    <Select2 name="edit" v-model="$v.form.province.$model" :options="provinces" @change="changeEventSelect2($event , 'province')"/>
                                </div>
                                <div class="error" v-if="submitStatus && !$v.form.province.required">Field tidak boleh kosong</div>
                            </div>
                        </div>
                        <div class="row-100 mbottom-20">
                            <h5 class="col-3 col-sm-12">Kota</h5>
                            <div class="col-9 col-sm-12">
                                <Select2 v-model="$v.form.city.$model" :options="city" @change="changeEventSelect2($event , 'city')"/>
                                <div class="error" v-if="submitStatus && !$v.form.city.required">Field tidak boleh kosong</div>
                            </div>
                        </div>
                        <div class="row-100 mbottom-20">
                            <h5 class="col-3">Daerah</h5>
                            <div class="col-9">
                                <Select2 v-model="$v.form.suburbs.$model" :options="suburbs" @change="changeEventSelect2($event , 'suburbs')"/>
                                <div class="error" v-if="submitStatus && !$v.form.suburbs.required">Field tidak boleh kosong</div>
                             </div>
                        </div>
                        <div class="row-100 mbottom-20">
                            <h5 class="col-3">Area</h5>
                            <div class="col-9">
                                <Select2 v-model="$v.form.area.$model" :options="area" @change="changeEventSelect2($event , 'area')"/>
                                <div class="error" v-if="submitStatus && !$v.form.area.required">Field tidak boleh kosong</div>
                            </div>
                        </div>
                        <div class="row-100 mbottom-20">
                            <h5 class="col-3">Kode Pos</h5>
                            <div class="col-9">
                                <input type="number" v-model="$v.form.zip_code.$model">
                                <div class="error" v-if="submitStatus && !$v.form.zip_code.required">Field tidak boleh kosong</div>
                            </div>
                        </div>
                        <div class="row-100 mbottom-20">
                            <h5 class="col-3">Label Alamat</h5>
                            <div class="col-9">
                                <input type="text" class="address" v-model.trim="$v.form.label_name.$model">
                                <div class="error" v-if="submitStatus && !$v.form.label_name.required">Field tidak boleh kosong</div>
                            </div>
                        </div>
                        
                        <div class="mbottom-25">
                            <div class="row-profile">
                                <div class="sub-menu">
                                    <div class="col">
                                        <a @click="saveAddress" class="btn-link">Update</a>
                                    </div>
                                    <div class="col">
                                        <a @click="setPrimaryAddress(item.itemid)" class="btn-link">Gunakan Sebagai Alamat Utama </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="row-profile">
                <div class="d-flex align-center">
                    <img style="width:20px;" class="mright-10" src="/img/ic_disclaimer.svg" alt="">
                    <small class="purple">Untuk penambahan alamat user, maksimal 5 (lima) alamat berbeda </small>
                </div>
            </div>
        </div>
        <modal-delete-confirmation :item='deleted_item'  :deleteFor="'address'" @dodelete="dodelete($event)"></modal-delete-confirmation>
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
        name: "AddressForm.vue",
        data(){
            return {
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
        mounted (){
            this.get_address_by_id()
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
                // $(".close-modal").css('background-image', 'url(/images/assets_close_white.svg?fd4f6a829a4cb73794efb4e6f4a1f7b8)');
                $(".close-modal").css('background-image', 'none');
                this.deleted_item = {index : index , item : item}
                $("#delete_modal_confirmation").fadeIn();
            },
            dodelete(event){
                if(event.for == 'address'){
                    $("#delete_modal_confirmation").fadeOut();
                    Customer.remove_address({id : event.item.item.itemid}).then(response => {
                        if(response.data.code == 200){
                            this.addresses.splice(event.item.index , 1);
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
                                if(response.data.code == 200){
                                    $("#modal_load").fadeOut()
                                    window.location.reload()
                                }
                            })
                        }else{
                            this.form.itemid = this.addresses[this.IndexOfAddress].itemid;
                            Customer.update_address(this.form).then(response => {
                                if(response.data.code == 200){
                                    this.addresses[this.IndexOfAddress] = response.data.data;
                                    // this.EditForm = false;
                                }else{
                                    alert(response.data.message);
                                }
                                this.disabledButton = false;
                                window.location.reload()

                            })
                        }
                        
                    }
                }
            },
            setPrimaryAddress(itemid , index){
                Customer.setAsPrimary({itemid : itemid}).then(response => {
                    if(response.data.code == 200){
                        $('#label-address').text(response.data.data.data.label_name)
                        $('#label-name').text(response.data.data.data.label_name)
                        $('#recepient-name').html(response.data.data.data.recipient_name)
                        $('#phone-number').html(response.data.data.data.phone_number)
                        $('#province').html(response.data.data.data.province.name+ ", &nbsp;")
                        $('#regency').html(response.data.data.data.regency.name+ ", &nbsp;")
                        $('#zip-code').html(response.data.data.data.zip_code)
                        $('#address-text').html(response.data.data.data.address+ " &nbsp;")
                        $('#address').html(response.data.data.data.address+ " &nbsp;")
                        $("#change_address_modal").fadeOut(function () {
                            $(".overlay-primary").removeClass('overflow-hidden');
                            $("body").removeClass('overflow-hidden');
                        });
                        for(const item of this.addresses){
                            item.is_primary_address = false;
                        }
                        this.addresses[index].is_primary_address = true
                    }else{
                        alert(response.data.message);
                    }
                })
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
.select2-container {
    box-sizing: border-box;
    display: inline-block;
    margin: 0;
    position: relative;
    width: 100% !important;
    vertical-align: middle;
}
</style>