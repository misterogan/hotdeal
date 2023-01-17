<template>
    <div id="confirmation_delete_modal" class="modal">
            <div class="modal-dialog purple">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="confirmation">
                            <div class="row">
                                <h5>Pilihanmu akan dihapus</h5>
                            </div>
                            <div class="row">
                                <div class="btn-option">
                                    <button @click="doDelete">
                                        <img src="img/y.svg" alt="">
                                        <h6>Lanjutkan</h6>
                                    </button>
                                    <close-modal :attribute="'confirmation_delete_modal'"></close-modal>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    import CustomerAPi from '../../../apis/Customer'
    import CloseModal from '../component/CloseModal.vue'
    export default {
        name: "ModalDeleteProduct.vue",
        data() {
            return {
            }
        },
        props :['product' , 'status_deleted'],
        methods: {
            doDelete(){
                if(this.status_deleted){
                    CustomerAPi.deleteCart({ pdid : ['all'] , status : 'all'  }).then( response => {
                        if(response.data.code == 200){
                            $('.close-modal').click();
                            this.$emit('updateCarts' , response.data.data.count_cart)
                        }
                    });
                }else{
                    var productId = this.product.map(product => (product.product_details_id));
                    CustomerAPi.deleteCart({ pdid : productId , status : 'item'  }).then( response => {
                        if(response.data.code == 200){
                           $('.close-modal').click();
                            this.$emit('updateCarts' , response.data.data.count_cart)
                        }
                    });
                }
                 this.$emit('after_delete' , {});
            }
           
        },
        mounted(){
            
        },
        components:{
                CloseModal
        }
    }
</script>

<style>

</style>