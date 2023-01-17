<template>
    <button class="btn-cta pr-20 pl-20" v-if="product.detail.variant_key_1 != ''">
        <router-link :to="'/product-detail/'+product.slug">
            <img src="img/ic_repurchase_white.svg" alt="">
            masukkan keranjang
        </router-link>
    </button>
    <button class="btn-cta pr-20 pl-20" v-else @click="addcart">
        <img src="img/ic_repurchase_white.svg" alt="">
        masukkan keranjang
    </button>
</template>
<script>
    import CustomerAPi from '../../../../apis/Customer'
    import Message from '../../../../utils/Message';
    export default {
        name: "WishListButtonBuyNow.vue",
        props : ['product'],
        methods: {
            addcart(){
                let id = this.product.detail.variant_data._.pdid;
                if(id === undefined || id === null){
                    alert('Produk tidak ditemukan');
                }else{
                    CustomerAPi.cart({ pdid : id , qty : 1 , from_add_cart : false}).then( response => {
                        if(response.data.code == '200'){
                            window.location.href = '/cart'
                        }else{
                            Message.alert('Produk tidak dapat dibeli saat ini' , 'Informasi' , 1500)
                        }
                    });
                }
                
            }
        }
    }
</script>

<style scoped>

</style>