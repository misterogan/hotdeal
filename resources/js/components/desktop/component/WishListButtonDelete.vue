<template>
    <button @click="remove">
        <img src="img/ic_trash.svg" alt="">
    </button>
</template>
<script>
    import CustomerAPi from '../../../apis/Customer'
    export default {
        name: "WishListButtonDelete.vue",
        props : ['product' , 'action' ,'index'],
        methods: {
            remove(){
                if(this.action == 'wishlist'){
                    CustomerAPi.wish({ slug : this.product.slug}).then( response => {
                        this.$emit('delete_wish' , this.index);
                    });
                }        
            }
        }
    }
</script>

<style scoped>

</style>