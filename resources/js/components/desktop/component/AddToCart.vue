<template>
        <div>
            <button class="detail-btn-cart" @click="addcart()" :class="Clases"></button>
            <modal-alert-success></modal-alert-success>
        </div>
</template>

<script>
    import Message from '../../../utils/Message'
    import CustomerAPi from '../../../apis/Customer'
    import ModalAlertSuccess from '../modal/ModalAlertSuccess.vue'

    export default {
        name: "AddToCart",
        data(){
            return {
                count_cart : 0 ,
            }
        },
        components :{
            Message,
            ModalAlertSuccess
        },
        props :['Clases', 'key_item_1' , 'key_item_2', 'item', 'quantity'],
        mounted(){  
        },  
        methods : {
            addcart(){
                if(!localStorage.getItem('auth')){
                    return Message.alert('Silahkan login terlebih dahulu.', 'Informasi', 1500);
                }
                let id = this.key_item_1.replaceAll(' ' ,'_')+'_'+this.key_item_2.replaceAll(' ' ,'_');
                if(this.item[id] == undefined){
                    Message.alert('Pilih produk yang ingin dimasukkan ke dalam keranjang', 'Informasi' , 1500);
                }else{
                    CustomerAPi.cart({ pdid : this.item[id].pdid , qty : this.quantity , from_add_cart : true}).then( response => {
                       if(response.data.code == 200){
                            Message.alert2('Ditambahkan ke keranjang' , 'Lihat Keranjang' , '/img/animation_success_cart.svg', 'cart')
                            this.$emit('updateCarts', response.data.data.count_cart);
                            fbq('track', 'AddToCart');
                            $('.slick-arrow').css('z-index', '0');
                            setTimeout(() => {
                                $('.slick-arrow').css('z-index', '11');
                            }, 1400);
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
    }
</script>