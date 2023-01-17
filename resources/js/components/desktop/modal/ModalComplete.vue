<template>
    <div id="complete_modal" class="modal">
        <div class="modal-dialog w-350">
            <div class="modal-body" v-if="Object.keys(data).length > 0">
                <div class="content-modal">
                    <div class="notify-modal">
                        <img class="ilustrasi" src="/img/ilustrasi_sukses.svg">
                        <h5 class="mtop-20">terimakasih pesanan kamu telah selesai</h5>
                        <h5>beri ulasan produk?</h5>
                        <div class="row-btn">
                            <button class="btn-secondary" @click="close_modal">nanti saja</button>
                            <button href="javascript:void(0)" @click="show_modal_review(data.inv)" class="btn-primary">
                                <img src="/img/ic_ulasan.svg" alt="">
                                beri ulasan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <modal-review :data="product_for_review"></modal-review> -->
    </div>
</template>

<script>
    import CloseModal from '../component/CloseModal.vue'
    export default {
        name: "ModalComplete.vue",
        props :['data', 'review'],
        data() {
            return {
                product_for_review : {}
            }
        },
        methods: {
            close_modal(){
                $('.modal').fadeOut();
                $("body").addClass('overflow-scroll');
            },
            show_modal_review(inv){
                this.product_for_review = {vendor : this.data.vendor.vendor_id, inv : inv , products : this.data.products, order_details_id : this.data.order_details_id, review : this.data.review}
                $("#review_modal").fadeIn(function () {
                    $("#review_modal").addClass('overflow-scroll')
                    $("body").addClass('overflow-hidden');
                });
                $("#complete_modal").fadeOut(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            modal_review(){
                this.show_modal_review()
            },
        },
        components:{
                CloseModal
        }
    }
</script>

<style>

</style>