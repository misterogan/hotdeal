<template>
   
</template>

<script>
    import CustomerAPi from '../../../apis/Customer'
    export default {
        name: "AddToCart",
        props :['quantity','index'],
        data(){
            return {
                quantity : Number,
                index : Number,
            }
        },
        mounted(){  
        },
        methods : {
            decQuantity(){
                if(this.quantity > 1){
                    this.quantity--;
                }
            },
            addQuantity(){
                 this.quantity++;
                 
            },
            updateQuantity(event){
                this.quantity = event.target.value;
            },
        }
    }
</script>