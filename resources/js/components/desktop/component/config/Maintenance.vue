<template>
    <div class="not-found">
        <div class="logo text-center">
            <router-link class="link" to="/">
                <img src="/img/logo.svg" alt="">
            </router-link>
        </div>
        <img class="animation maintenance" src="https://s3.ap-southeast-1.amazonaws.com/hotdeal.cdn/under_maintenance.png" alt="">
        <h4>Dear Hotters,</h4>
        <p> Mohon maaf website HOTDEAL tidak bisa diakses untuk sementara waktu. 
            Terima kasih<br/>
            Kami akan segera kembali</p>
    </div>
</template>

<script>
    export default {
        name: "maintenance",
    }
</script>