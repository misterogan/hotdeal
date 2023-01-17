<template>
    <div id="wrap-profile">
        <div class="profile-dd pop-over">
            <h5 class="title-notif">Akun</h5>
            <ul class="profile-ul">
                <li><router-link class="link" to="/vendor/store/account">Akun</router-link></li>
                <li><router-link class="link" to="/vendor/store/address">Alamat Toko</router-link></li>
                <!-- <li><router-link class="link" to="">Informasi Penghasilan</router-link></li> -->
                <li class="link" @click="logout">Logout</li>
            </ul>
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
                    })
                }
            },
        }
    }
</script>

<style scoped>

</style>