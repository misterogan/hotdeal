<template>
    <div class="not-found">
        <div class="logo">
            <router-link class="link" to="/">
                <img src="/img/logo.svg" alt="">
            </router-link>
        </div>
        <div>
            <img class="animation" src="/img/animation_empty_page.svg" alt="">
        </div>
        <h4>Kami Tidak Menemukan Halaman Yang Anda Cari</h4>
        <router-link class="link" to="/">
            <button>
                    kembali
            </button>
        </router-link>
    </div>
</template>

<script>
    export default {
        name: "NotFound.vue",
    }
</script>

<style scoped>

</style>