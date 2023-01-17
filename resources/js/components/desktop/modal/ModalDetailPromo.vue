<template>
    <div id="promo_detail_modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-body">
                <span class="close-modal"></span>
                <div class="content-modal">
                    <div class="banner-promo">
                        <img :src="data.image" alt="">
                    </div>
                    <div class="body-promo">
                        <h4>{{data.voucher_name}}</h4>
                        <h6>{{data.voucher_description}}</h6>
<!--                        <div class="row-button">-->
<!--                            <button>gunakan sekarang</button>-->
<!--                            <button class="btn-share"><img src="/img/ic_share.svg" alt=""></button>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import CloseModal from '../component/CloseModal.vue'

export default {
    name: "ModalDetailPromo.vue",
    props:['data'],
    
    components:{
        CloseModal
    }
}
</script>

<style>
</style>
