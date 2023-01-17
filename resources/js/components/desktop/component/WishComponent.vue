<template>
    <div>
        <button class="component-wish" @click="wish()" :class="wish_active"></button>
        <modal-alert-success></modal-alert-success>
    </div>
</template>

<script>
    import CustomerAPi from '../../../apis/Customer'
    import Message from '../../../utils/Message'
    import ModalAlertSuccess from '../modal/ModalAlertSuccess.vue'

    export default {
        name: "WishComponent",
        props :['clases','slug','active','type'],
        data(){
            return {
                wish_active : '',
                wishActive: ''
            }
        },
        components :{
            ModalAlertSuccess
        },
        mounted(){ 
            this.clases === 'active' ? this.wish_active = 'active' :  this.wish_active = '';
        },    
        methods : {
            wish(){
                CustomerAPi.wish({ slug : this.slug}).then( response => {
                    if(response.data.code == 200){
                        this.$emit('wish', response.data.data.status);
                        this.wish_active = this.wish_active == 'active' ? '' : 'active';
                        this.wishActive = response.data.data;
                        
                        if(this.wishActive === 'active'){
                            Message.alert2('Ditambahkan ke wishlist' , 'Lihat Wishlist' , '/img/animation_empty_wishlist.svg', 'wishlist')
                        } else if(this.wishActive === 'deleted'){
                            Message.alert2('Behasil menghapus wishlist' , 'Lihat Wishlist' , '/img/animation_empty_wishlist.svg', 'wishlist')
                        }
                        fbq('track', 'AddToWishlist');
                        $('.slick-arrow').css('z-index', '0');
                        // $('.row-btn').css('transform', 'unset')
                        setTimeout(() => {
                            $('.slick-arrow').css('z-index', '11');
                            // $('.row-btn').css('transform', 'translate(-50%, 0)')
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
</script>