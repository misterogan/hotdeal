<template>
    <div class="auth">
        <div class="header-single-page">
            <button onclick="history.back()" to="/" class="button-back"></button>
            <router-link to="/" class="button-home"><img src="/img/logo.svg" alt=""></router-link>
        </div>
        <transition name="fade" mode="out-in">
            <div class="container-form success">
                <div class="row-100">
                    <div class="w-100">
                        <h5>Voucher Berhasil di Redeem, Dan Sudah Ditambahkan ke Hot Poin Milikmu </h5>
                        <img src="/img/animation_success_redeem.svg" alt="">
                        <h5 class="mbottom-10">Redeem Voucher</h5>
                        <div class="box-info">
                            <div class="mbottom-5">
                                Kode Voucher
                                <b>GHJ78XU90UKl45561XJL</b>
                            </div>
                            <div class="mbottom-5">
                                Nominal
                                <b>Rp 100.000</b>
                            </div>
                            <div>
                                Total Hot Point
                                <b>200.000</b>
                            </div>
                        </div>
                        <button class="btn-primary m-auto">lanjut belanja</button>
                        <button class="btn-link d-flex">cek hot point</button>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>

</script>
