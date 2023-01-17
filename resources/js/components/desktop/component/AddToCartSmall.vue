<template>
    <div>
        <div class="add-to-cart">
            <button class="cart" v-if="product && product.having_variant !== true" @click="addCart(product.having_variant)">
                <img src="/img/add_to_cart.svg" alt="">
            </button>
            <button class="cart" v-else>
                <a :href="'/product-detail/'+product.slug"><img src="/img/add_to_cart.svg" alt=""></a>
            </button>
        </div>
        <modal-alert-success></modal-alert-success>
    </div>
</template>

<script>
    import CustomerAPi from '../../../apis/Customer'
    import Message from '../../../utils/Message'
    import ModalAlertSuccess from '../modal/ModalAlertSuccess.vue'

    export default {
        name: "AddToCartSmall",
        data(){
            return {
                count_cart : 0 
            }
        },
        components :{
            Message,
            ModalAlertSuccess
        },
        props :['product'],
        mounted(){
        },  
        methods : {
            addCart(id){
                CustomerAPi.cart({ pdid : id , qty : 1 , from_add_cart : false}).then( response => {
                    if(response.data.code == 200){
                        Message.alert2("Ditambahkan ke Keranjang." , 'Lihat Keranjang' , '/img/animation_success_cart.svg', 'cart', 1500)
                        this.$emit('updateCarts', response.data.data.count_cart);
                        fbq('track', 'AddToCart');
                        setTimeout(() => {
                                $('#success_alert_modal').fadeOut();
                                $("body").removeClass('overflow-hidden');
                            }, 1000);
                    }else{
                        if(response.data.message != undefined){
                            Message.alert(response.data.message , 'Informasi' , 1500)
                        }else{
                            Message.alert('Silahkan login terlebih dahulu' , 'Informasi' , 1500)
                        }
                    }
                });
            }
        }
    }
</script>