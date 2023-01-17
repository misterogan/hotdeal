<template>
    <div id="wrap-profile" class="wrap">
        <div class="profile-dd pop-over">
            <h5 class="title-notif">Akun</h5>
            <ul class="profile-ul">
                <li><router-link class="link" to="/personal-info">Profil</router-link></li>
                <li><router-link class="link" to="/transactions/list-transaction">Status Pembelian</router-link></li>
                <li><router-link class="link" to="/wishlist">Wishlist</router-link></li>
                <li class="link mtop-25" @click="logout">
                    keluar
                    <img class="logout mleft-5" src="/img/assets_hotdeal_logout.svg" alt="" width="15">
                </li>
            </ul>
            <!-- <div class="arrow"></div> -->
        </div>
    </div>
</template>

<script>
    import User from '../../../apis/User'
    export default {
        data(){
            return{
                is_login : User.is_login()
            }
        },
        methods : {
            logout(){
                localStorage.removeItem('auth');
                if(this.is_login){
                    User.logout().then(()=>{
                        localStorage.removeItem('auth');
                        this.is_login = false;
                        this.$router.push({name:'login'});
                        localStorage.removeItem('verify-phone')
                    })
                }
            },
        }
    }
</script>

<style scoped>

</style>