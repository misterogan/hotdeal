<template>
    <div class="direct-page">
        <blank-page :message="'Pembayaran pemesanan anda gagal'" :image="'img/animation_payment_failed.svg'"></blank-page>
        <div class="button-back">
            <button class="btn-secondary">
                <router-link to="/transactions/list-transaction">
                    lihat detail transaksi
                </router-link>
            </button>
            <button class="btn-primary">
                <router-link to="/">
                    belanja lagi
                </router-link>
            </button>
        </div>
    </div>
</template>

<script>
    import BlankPage from '../desktop/BlankPage.vue'

    export default {
        name: "PaymentSuccess.vue",
        components: {
            BlankPage
        }
    }
</script>

<style scoped>

</style>