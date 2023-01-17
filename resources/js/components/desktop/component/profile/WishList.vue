<template>
    <div class="col-20p">
        <div class="box-filter-purple">
            <div class="content">
                <h4>Akun</h4>
                <ul>
                    <li class="active"><router-link class="btn-link" to="/personal-info">Profile</router-link></li>
                    <li><router-link class="btn-link" to="/personal-info">Poin</router-link></li>
                    <li><router-link class="btn-link" to="/transaction-list">Daftar Transaksi</router-link></li>
                </ul>
                <div class="logout" @click="logout">
                    <img src="/img/ic_logout.svg" alt="">
                    <router-link class="btn-link" to="/">Logout</router-link>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import User from '../../../../apis/User'
    export default {
        name: "ProfileMenu.vue",
        methods: {
            logout(){
                User.logout();
            }
        }
    }
</script>

<style scoped>

</style>