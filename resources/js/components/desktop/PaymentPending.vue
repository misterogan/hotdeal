~<template>
    <div class="direct-page">
        <blank-page :message="'Silakan periksa aplikasi OVO Anda di bawah “Pemberitahuan” dan konfirmasi pembayaran dalam waktu 55 detik'" :image="'/img/animation_payment_success.svg'"></blank-page>
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
    import BlankPage from './BlankPage.vue'

    export default {
        name: "PaymentSuccess.vue",
        components: {
            BlankPage
        }
    }
</script>

<style scoped>

</style>
