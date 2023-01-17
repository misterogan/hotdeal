<template>
   <div class="payment-method">
        <div class="mbottom-30" v-if="Object.keys(payment_gateway).length > 0">
            <h5 class="grey">VIRTUAL ACCOUNT</h5>
            <div class="input-radio" v-for="(item , index) in payment_gateway" :key="index">
                <div class="radio">
                    <input type="radio" v-model="selected_payment" :value="item.code" name="virtual_account" :id="item.code" />
                    <label :for="item.code"></label>
                </div>
                <div class="label">
                    {{item.name}}
                </div>
            </div>
            
        </div>
    </div>
</template>
<script> 
    import apiCustomer from '../../../../apis/Customer'
    export default {
        data(){
            return {
                payment_gateway : {},
                selected_payment : null
            }
        },
        name: "CheckoutPaymentGateway.vue",
        mounted(){
            this.list_payment_gateway()
        },
        methods: {
            list_payment_gateway(){
                apiCustomer.list_PG().then(response => {
                    this.payment_gateway = response.data.data
                })
            },

        }
    }
</script>