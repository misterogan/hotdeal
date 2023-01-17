<template>
    <div>
        <div class="main-content">
            <div class="center-menu" style="width:100%">
                <div class="main-menu">
                    <div class="box address-store">
                        <h3>alamat toko</h3>
                        <div class="input-data">
                            <form action="" v-if="Object.keys(profile).length > 0">
                                <div class="opsi">
                                    <h5>nama toko</h5>
                                    <!-- <h5>alamat toko</h5> -->
                                    <!-- <h5>alamat pengembalian</h5> -->
                                </div>
                                <input type="text" name="name" v-model="form.vendor_name" placeholder="masukkan nama toko">  
                                <h5>nomor telepon</h5>
                                <input type="text" v-model="form.phone" name="categories" placeholder="masukkan nomor telepon">
                                 <!-- <h5>Kode Pos</h5>
                                 <input type="text" v-model="profile.zip_code" name="zip_code" placeholder="masukkan kode pos"> -->
                                <h5>alamat</h5>
                                <textarea v-model="form.address" name="" id="" rows="6" placeholder="Tulis deskripsi produk di sini">{{form.address}}</textarea>
                             </form>
                             
                              <div class="input-address">
                                    <form>
                                        <h5>provinsi</h5>
                                         <Select2 :options="provinces" @change="changeEventSelect2($event , 'province')" v-model="form.province"/>
                                      </form>
                                    <form>
                                    <h5>Kota</h5>
                                        <Select2 :options="city" @change="changeEventSelect2($event , 'city')" v-model="form.city" />
                                    </form>
                                </div>
                                <div class="input-address">
                                    <form>
                                        <h5>Daerah</h5>
                                         <Select2 :options="suburbs" @change="changeEventSelect2($event , 'suburbs')" v-model="form.suburbs"/>
                                      </form>
                                    <form>
                                    <h5>Area</h5>
                                         <Select2 :options="area" @change="changeEventSelect2($event , 'area')" v-model="form.area"/>
                                    </form>
                                </div>
                            
                        </div>
                        <div class="button">
                            <button class="btn-primary" @click="updateVendorAddress">
                                Simpan Alamat
                            </button>
                             <button class="btn-primary">
                                <router-link style="color:#fff;" to="/vendor/store/address/" class="btn-primary-seller">
                                    Batalkan
                                </router-link>
                             </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Vendor from "../../apis/Vendor";
import Select2 from 'v-select2-component';
import apiMaster from '../../apis/Master'
import Message from '../../utils/Message'

export default {
    data() {
        return {
            save_btn : true,
            error: {},
            profile: {},
            provinces : [],
            city : [],
            suburbs :[],
            area : [],
            selected_city : '',
            selected_suburbs : '',
            selected_area : '',
            form : {
                vendor_name : '',
                phone : '',
                address : '',
                city : '',
                province : '',
                zip_code : '',
                area: '',
                suburbs : ''
            }


        }
    },
    created: function() {
        this.get_vendor();
        this.province_data()
    },
    methods: {
        get_vendor() {
            this.save_btn = false
            Vendor.profile().then(response => {
                if(response.data.code != 200){
                    localStorage.removeItem('auth');
                }else if(response.data.code == 200){
                    this.profile = response.data.data;
                    this.loading_profile = false;
                    if(this.profile.province != null){
                        this.form.province = this.profile.province.id
                    }
                    this.form.address = this.profile.address
                    this.form.vendor_name = this.profile.name
                    this.form.phone = this.profile.phone

                    if(this.profile.city != null){
                        this.city.push({id:this.profile.city.id , name :this.profile.city.name , text : this.profile.city.name});
                        this.form.city = this.profile.city.id
                    }
                    if(this.profile.suburbs != null){
                        this.suburbs.push({id:this.profile.suburbs.id , name :this.profile.suburbs.name , text : this.profile.suburbs.name});
                        this.form.suburbs = this.profile.suburbs.id
                    }
                    if(this.profile.area != null){
                        this.area.push({id:this.profile.area.id , name :this.profile.area.name , text : this.profile.area.name});
                        this.form.area = this.profile.area.id
                    }
                }else{
                     localStorage.removeItem('auth');
                }  
                this.save_btn = true             
            })
        },

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
                this.form.province = val
                this.city_data();
            }
            if(type == 'city'){
                this.form.city = val
                this.suburbs_data();
            }
            if(type == 'suburbs'){
                this.form.suburbs = val
                this.area_data();   
            }
            if(type == 'area'){
                this.form.area = val
            }
        },
        updateVendorAddress(){
            Vendor.updateVendorAddress(this.form).then(response => {
                if(response.data.code == 200){
                    Message.alert(response.data.message , "Informasi" , 3000);
                }else{
                    Message.alert(response.data.message , "Informasi" , 3000);
                }
            })
        }
    },
    components : {
            Select2,
            Message
        }
}
</script>
