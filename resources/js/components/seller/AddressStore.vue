<template>
    <div>
        <div class="main-content">
            <div class="center-menu" style="width:100%">
                <div class="main-menu">
                    <div class="box address-store">
                        <h3>alamat toko</h3>
                        <div class="input-data address">
                            <form action="">
                                <div class="left">
                                    <h5>nama toko</h5>
                                </div>
                                <div class="right">
                                    <h5 v-if="profile">{{ profile.name }}</h5>
                                </div>
                            </form>
                            <form action="">
                                <div class="left">
                                    <h5>nomor telepon</h5>
                                </div>
                                <div class="right">
                                    <h5 v-if="profile">{{ profile.phone }}</h5>
                                </div>
                            </form>
                            <form action="">
                                <div class="left">
                                    <h5>alamat</h5>
                                </div>
                                <div class="right">
                                    <h5 v-if="profile">{{ profile.address }}</h5>
                                </div>
                            </form>
                        </div>
                        <div class="button" style="width:20%">
                            <router-link to="/vendor/store/address/edit" style="width:100%">
                                <button class="btn-primary" style="width:100%">
                                    ubah alamat
                                </button>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vendor from "../../apis/Vendor";

export default {
    data() {
        return {
            error: {},
            profile: {},
        }
    },
    created: function() {
        this.get_vendor();
    },
    methods: {
        get_vendor() {
            Vendor.profile().then(response => {
                if(response.data.code != 200){
                    //localStorage.removeItem('auth');
                }else if(response.data.code == 200){
                    this.profile = response.data.data;
                    this.loading_profile = false;
                    this.form.province = this.profile.vendor.province_id
                }else{
                     //localStorage.removeItem('auth');
                }
            })
        }
    }
}
</script>

