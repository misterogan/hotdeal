<template>
    <div class="flex-basis-50">
        <div class="mbottom-10" v-if="is_loading">
            <div class="ph-row">
                <div class="ph-col-12 ph-h610 bg-placeholder mright-10 rounded-lg"></div>
            </div>
        </div>
        <div class="box-filter-purple">
            <h4>Akun</h4>
            <ul>
                <li :class="route_active == 'personalinfo' ? 'active' :''" @click="route('personal-info')">
                    <router-link class="btn-link" to="/personal-info">
                        <img src="/img/icon_profile.svg" alt=""> profil
                    </router-link>
                </li>
                <li :class="route_active == 'hotpoint' ? 'active' :''" @click="route('hotpoint')">
                    <router-link class="btn-link" to="/hotpoint">
                        <img src="/img/ic_hotpoint.svg" alt=""> hot point
                    </router-link>
                </li>
                <li :class="route_active == 'invite' ? 'active' :''" @click="route('invite-friends')">
                    <router-link class="btn-link" to="/invite-friends">
                        <img src="/img/ic_invite.svg" alt=""> undang teman
                    </router-link>
                </li>
                <li :class="route_active == 'list-transaction' ? 'active' :''" @click="route('list-transaction')">
                    <router-link class="btn-link" to="/transactions/list-transaction">
                        <img src="/img/icon_transaction.svg" alt=""> daftar transaksi
                    </router-link>
                </li>
                <li :class="route_active == 'personalinfoaddress' ? 'active' :''" @click="route('personal-info-address')">
                    <router-link class="btn-link" to="/personal-info-address">
                        <img src="/img/icon_address.svg" alt=""> daftar alamat
                    </router-link>
                </li>
                <li :class="route_active == 'kuponrejeki' ? 'active' :''" @click="route('kupon-rejeki')">
                    <router-link class="btn-link" to="/kupon-rejeki">
                        <img src="/img/badge_rejeki_nomplok.svg" alt="" width="40"> rejeki nomplok
                    </router-link>
                </li>
                <li :class="route_active == 'password' ? 'active' :''" @click="route('password')">
                    <router-link class="btn-link" to="/personal-info-edit/password">
                        <img src="/img/icon_security.svg" alt="">keamanan 
                    </router-link>
                </li>
                <li class="pending badge" :class="route_active == 'pending-transaction' ? 'active' :''" @click="route('pending-transaction')">
                    <router-link class="btn-link" to="/transactions/pending-transaction">
                        <img class="icon_pending" src="/img/icon_pending.svg" alt="">
                        menunggu<br>pembayaran
                        <div class="total-pending" v-if="count > 0">{{count}}</div>
                    </router-link>
                </li>
                <li class="logout" @click="logout">
                    <a class="btn-link" @click="logout">
                        <img src="/img/ic_logout.svg" alt="">
                        Keluar
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>


<script>
    import User from '../../../../apis/User'
    export default {
        name: "ProfileMenu.vue",
        data(){
            return {
                route_active : '',
                is_loading : true,
                profile: {}
            }
        },
        props : ['count'],
        methods: {
            logout(){
                localStorage.removeItem('auth');
                User.logout().then(()=>{
                    localStorage.removeItem('auth');
                    this.$router.push({name:'login'});
                })
            },
            route(route){
                this.route_active = route
            },
            get_profile(){
              User.profile().then(response => {
                  if(response.status === 401){
                       localStorage.removeItem('auth');
                  }
                  this.profile = response.data;
                  
                  if(this.profile.is_vendor === true){
                        return this.$router.push({name:'VendorDashboard'})
                  }
                  this.loading_profile = false;
              })
            },
        },
        mounted() {
            this.is_loading = false;
            this.route_active = this.$route.name;
            this.get_profile();
        }
    }
</script>

<style scoped>

</style>