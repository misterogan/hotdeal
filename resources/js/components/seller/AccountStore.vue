<template>
    <div>
        <div class="main-content">
            <div class="center-menu">
                <div class="main-menu">
                    <div class="box address-store">
                            <h3>akun</h3>
                            <div class="input-data address">
                                <form action="">
                                    <div class="left">
                                        <h5>user name</h5>
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
                                        <h5>email</h5>
                                    </div>
                                    <div class="right">
                                        <h5 class="lowercase" v-if="profile">{{ profile.email }}</h5>
                                    </div>
                                </form>
                            </div>
                            <div class="button" style="width:20%">
                                <router-link to="/vendor/store/account/edit" style="width:100%">
                                    <button class="btn-primary" style="width:100%">
                                        ubah akun
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
    import User from '../../apis/User'

    export default {
        data() {
            return {
                error: {},
                profile: {},
            }
        },
        created: function() {
            this.get_profile();
        },
        methods: {
            get_profile(){
                User.profile().then(response => {
                    if(response.status === 401){
                        localStorage.removeItem('auth');
                    }
                    this.profile = response.data;
                    this.loading_profile = false;
                })
            },
        }
    }
</script>
